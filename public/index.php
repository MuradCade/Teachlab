<?php

use function DI\autowire;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use DI\ContainerBuilder;
use App\Config\Eloquent;
use Slim\Factory\AppFactory;
use Twig\Extension\DebugExtension;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
// mailtrap namespaces
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use App\Controllers\Services\MailerService;
use Symfony\Component\Mailer\MailerInterface;

//laravel debugger
use function Symfony\Component\VarDumper\dump;

require __DIR__ . '/../vendor/autoload.php';
$config =  require __DIR__ . '/../env.php';

// Define a writable log file path relative to your project
ini_set('error_log', __DIR__ . '/../logs/php-error.log');
ini_set('log_errors', '0');
ini_set('display_errors', '1');  // or '1' during dev to show errors in browser
error_reporting(E_ALL);

$builder = new ContainerBuilder();

//register middlewares and make it useble in the router
$builder->addDefinitions([
    // load env variables
    'config' => $config,
    // registering middleware
    \App\Middlewares\MaintainanceMode::class => \DI\autowire(\App\Middlewares\MaintainanceMode::class),
    \App\Middlewares\AuthMiddleware::class => \DI\autowire(\App\Middlewares\AuthMiddleware::class),
    \App\Middlewares\GuestMiddleware::class => \DI\autowire(\App\Middlewares\GuestMiddleware::class),
    \App\Middlewares\RemembermeMiddleware::class => \DI\autowire(\App\Middlewares\RemembermeMiddleware::class),
    // \App\Middlewares\Tenant\TenantMiddlewareHelper::class => \DI\autowire(\App\Middlewares\Tenant\TenantMiddlewareHelper::class),
    // \App\Middlewares\Tenant\AuthMiddleware::class => \DI\autowire(\App\Middlewares\Tenant\AuthMiddleware::class),
    // \App\Middlewares\Tenant\GuestMiddleware::class => \DI\autowire(\App\Middlewares\Tenant\GuestMiddleware::class),
    // \App\Middlewares\Tenant\AuthorizationMiddleware::class => \DI\autowire(\App\Middlewares\Tenant\AuthorizationMiddleware::class),

    // Bind ResponseFactoryInterface to Slim's ResponseFactory
    ResponseFactoryInterface::class => autowire(ResponseFactory::class),




    // Mailtrap Mailer binding
    MailerInterface::class => function ($c) {
        $mailtrap = $c->get('config')['MAIL']['MAILTRAP'];

        $dsn = sprintf(
            'smtp://%s:%s@%s:%s',
            $mailtrap['USERNAME'],
            $mailtrap['PASSWORD'],
            $mailtrap['HOST'],
            $mailtrap['PORT']
        );

        // Fully qualified class
        return new \Symfony\Component\Mailer\Mailer(
            \Symfony\Component\Mailer\Transport::fromDsn($dsn)
        );
    },

    // Your service autowire
    MailerService::class => \DI\autowire(MailerService::class),


]);

$container = $builder->build();


// Register elequent orm
Eloquent::setup($container->get('config'));



// Set container for AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

//register session
$app->add(
    new \Slim\Middleware\Session([
        'name' => 'teachlabs',
        'autorefresh' => true,
        'lifetime' => '1 hour',
        // 'secure' => true,      // only send cookie over HTTPS
        'httponly' => true,    // prevent JS access
        'path' => '/',
        'samesite' => 'Lax'
    ])
);

// Register Twig loader and view engine
$container->set(LoaderInterface::class, function () {
    return new FilesystemLoader(__DIR__ . '/../views');
});

$container->set(Twig::class, function ($c) use ($app) {
    $twig = new Twig(
        $c->get(LoaderInterface::class),
        ['cache' => false, 'debug' => true]
    );

    // Enable Twig debugging
    $twig->addExtension(new DebugExtension());
    // enables us to get assets of css and js from inside the public folder with dynamic base path
    $twig->getEnvironment()->addGlobal('base_path', $app->getBasePath());

    return $twig;
});



// Set base path (adjust if needed)
// $app->setBasePath('/slim_projects/simple_slim');
// var_dump();
// $app->setBasePath('lmslite.test');
// $app->setBasePath(dirname(dirname($_SERVER['SCRIPT_NAME'])));
// $app->setBasePath(dirname(dirname($_SERVER['SCRIPT_NAME'])));
$app->setBasePath('');

// is global function that handles accessing storage folder in the public directory
// storage_path function  is used to save file/image in storage folder in the public directory
// storage_url is used to display image from the folder it stored in 
if (!function_exists('storage_url')) {
    function storage_url(string $path = ''): string
    {
        global $app;
        $base = $app->getBasePath();

        // URL path (public storage folder)
        $storage = rtrim($base, '/') . '/storage';

        return $path
            ? $storage . '/' . ltrim($path, '/')
            : $storage;
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        // Absolute filesystem path
        return __DIR__ . '/storage' . ($path ? '/' . ltrim($path, '/') : '');
    }
}
// storage ends here 


// $app->setBasePath(dirname('/'));
// get base path
\App\Config\BasePath::$basePath = $_SERVER['HTTP_HOST'];
// Add Twig middleware
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

// Add error handling middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// custom error handler
$customErrorHandler = function (
    \Psr\Http\Message\ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = new Response();
    $view = $app->getContainer()->get(Twig::class);

    if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
        $response->getBody()->write(
            $view->fetch('pages/404.twig')
        );
        return $response->withStatus(404);
    }

    if ($exception instanceof \Slim\Exception\HttpMethodNotAllowedException) {
        $response->getBody()->write(
            $view->fetch('pages/405.twig')
        );
        return $response->withStatus(405);
    }

    // Fallback for all other errors
    $response->getBody()->write(
        $view->fetch('pages/500.twig', ['error' => $exception->getMessage()])
    );
    // $response->getBody()->write('500 - Internal Server Error: ' . );
    return $response->withStatus(500);
};

// Register the custom error handler
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

// Load route definitions
(require __DIR__ . '/../routes/web.php')($app);


// Run Slim app
$app->run();
