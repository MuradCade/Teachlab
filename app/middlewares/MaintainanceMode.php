<?php

namespace App\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;
use Slim\Views\Twig;

class MaintainanceMode
{
    private array $config;
    private Twig $twig;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get('config');
        $this->twig   = $container->get(Twig::class);
    }
    public function __invoke(Request $request, Handler $handler): Response
    {
        $maintenance = $this->config['MAINTENANCE']['ENABLED'] ?? false;
        $whitelist   = $this->config['MAINTENANCE']['WHITELIST'] ?? [];

        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? '';

        if ($maintenance && !in_array($ip, $whitelist, true)) {
            $response = new SlimResponse();
            // Render Twig template into response body
            $response->getBody()->write(
                $this->twig->fetch('pages/maintenance.twig', [
                    'title' => 'Site Under Maintenance',
                    'message' => 'We’ll be back shortly. Thank you for your patience.'
                ])
            );
            return $response->withStatus(503)
                ->withHeader('Content-Type', 'text/html');
            // $response = new SlimResponse();
            // $response->getBody()->write('<h1>Site under maintenance</h1><p>Please try again later.</p>');
            // return $response->withStatus(503)
            //     ->withHeader('Content-Type', 'text/html');
        }

        return $handler->handle($request);
    }
}
