<?php

namespace App\Controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\Controllers\Services\CsrfService;
use Psr\Container\ContainerInterface;
use App\Controllers\Services\Toast;
use App\Controllers\Services\Redirector;
use App\Models\Users;
use App\Controllers\Services\RoleEnum;
use App\Controllers\Services\GenerateUuid;
use App\Controllers\Services\Mailtemplate;
use App\Models\VerifyEmail;
use App\Controllers\Services\SessionHandler;
use App\Config\Rememberme;

class PageController
{
    protected Twig $view;
    protected CsrfService $csrf;
    protected ContainerInterface $container;
    protected SessionHandler $sessionhandler;
    protected Rememberme $rememberme;


    public function __construct(CsrfService $csrf, Twig $view, ContainerInterface $container, SessionHandler $sessionhandler, Rememberme $rememberme)
    {
        $this->csrf = $csrf;
        $this->view = $view;
        $this->container = $container;
        $this->sessionhandler = $sessionhandler;
        $this->rememberme = $rememberme;
    }

    // homepage
    public function indexHome(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $current_page = 'home';
        return $this->view->render($response, 'pages/index.twig', ['currentpage' => $current_page]);
    }
    // render create account page
    public function indexCreateAccount(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $toast = new Toast();
        $token = $this->csrf->generateToken();

        return $this->view->render($response, 'pages/register.twig', ['toast' => $toast, 'csrf_token' => $token]);
    }
    // login page
    public function indexLogin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $toast = new Toast();
        $token = $this->csrf->generateToken();
        $current_page = 'login';
        return $this->view->render($response, 'pages/login.twig', ['currentpage' => $current_page, 'toast' => $toast, 'csrf_token' => $token]);
    }


    public function handleAccountCreation(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $errors = [];
        $toast = new Toast();
        $data = $request->getParsedBody();
        // container help us get the configuration file content because we have registered it in the container in public/index.php
        $config = $this->container->get('config');
        $siteurl = $config['DOMAIN']['URL'];



        // store input field values in variables
        $fullname = trim($data['fullname']);
        $email = trim($data['email']);
        $password = trim($data['password']);
        $csrf_token = $data['csrf_token'] ?? '';
        // fetch user by email to check if email already exists
        $email_exist = Users::where('email', $email)->first();

        // validate input fields
        if (!$this->csrf->verify($csrf_token)) {
            $errors['csrf_token'] = 'Invalid CSRF token. Please try again.';
        } else if (empty($fullname)) {
            $errors['fullname'] = 'Fullname Field is required';
        } else if (empty($email)) {
            $errors['email'] = 'Email Field is required';
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Please enter a valid email address';
        } else if (empty($password)) {
            $errors['password'] = 'Password Field is required';
        }
        // check if password is is less then 6 character and it should containt uppercase, lowercase, number, and symbol
        else if (
            strlen($password) < 6 && !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            $errors['password'] = 'Password must be at least 6 characters, Password must contain uppercase letter, lowercase letter,number, and symbol.';
        } //check if email already exists in database
        else if ($email_exist) {
            $errors['email'] = 'Email already exists. Please use a different email.';
        }

        // If there are validation errors, re-render the form with error messages
        if (!empty($errors)) {

            $token = $this->csrf->getToken();

            return $this->view->render($response, 'pages/register.twig', ['toast' => $toast, 'errors' => $errors, 'old' => $data, 'csrf_token' => $token]);
        }

        // If validation passes, proceed with account creation logic
        if (empty($errors)) {

            // only generate new userid if the id is found in db
            $generated_userid = GenerateUuid::generateuuid();
            do {
                $generated_userid = GenerateUuid::generateuuid();
                $check_idexists = Users::where('userid', $generated_userid)->first();
            } while ($check_idexists);

            // Proceed with account creation logic (e.g., save to database)
            $usermodel = Users::create([
                'userid' => $generated_userid,
                'fullname' => $fullname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => RoleEnum::teacher
            ]);

            // also save subscription information (by default all new users are on free plan))
            $subscriptionmodel = $usermodel->userSubscriptions()->create([
                'userid' => $usermodel->userid,
                'subscription_type' => 'free',
                'sub_plan' => 'monthly',
                'sub_status' => 'trial',
                'sub_start_date' => null,
                'sub_expire_date' => null,
                'is_used' => 'yes'
            ]);

            // generate email verification code
            $verification_code = bin2hex(random_bytes(16));
            // store email verification code in the database
            $usermodel->verifyEmails()->create([
                'userid' => $usermodel->userid,
                'token' => $verification_code
            ]);

            // store email to be send in the mail_queueu table so we can send it later
            $mailtemplate = Mailtemplate::accountVerificationTemplate($fullname, $verification_code, $siteurl);
            $usermodel->mailQueueu()->create([
                'subject' => 'Email Confrimation',
                'body' => $mailtemplate,
                'username' => $fullname,
                'useremail' => $email,
                'userid' => $usermodel->userid,
                'mail_type' => 'signup',
                'mail_status' => 'pending'
            ]);
        }

        $data = [];

        return Redirector::redirect_to('/congratulations');
        // return $this->view->render($response, 'pages/register.twig', ['session_handler' => $session_handler, 'toast' => $toast]);
        // dd($response);

    }

    // this page is rendered after account creation (its where we tell user to check email for verification)
    public function indexcongratspage(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        return $this->view->render($response, 'pages/congrats.twig');
    }

    // this method handles email verification 
    public function Verifyemail(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $emailToken = $queryParams['email_token'] ?? null;

        // No token provided
        if (!$emailToken) {
            return $this->view->render($response->withStatus(400), 'pages/verifyemail.twig', [
                'error' => 'Email token is missing.'
            ]);
        }

        // Find token that is not used
        $token_exist = VerifyEmail::where('token', $emailToken)
            ->where('is_used', 0)
            ->first();

        // Token not found or already used
        if (!$token_exist) {
            return $this->view->render($response, 'pages/verifyemail.twig', [
                'error' => 'Token is invalid or already used.'
            ]);
        }

        // Mark token as used
        $token_exist->token = null; // get rid of the token when email confirmation is completed
        $token_exist->is_used = 1;
        $token_exist->save();

        // Update the user who owns this token
        $user = $token_exist->user(); // belongsTo relationship
        if ($user) {
            $user->update([
                'is_verified' => 1
            ]);
        }

        // Success response
        return $this->view->render($response, 'pages/verifyemail.twig', [
            'success' => 'Your email has been successfully confirmed. You may now log in.'
        ]);
    }

    // handle users authentication
    public function handleLogin(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $current_page = 'login';
        $errors = [];
        $toast = new Toast();
        $data = $request->getParsedBody();
        $remembermeinput = isset($data['rememberme']);
        $csrf_token = trim($data['csrf_token']);
        $email = trim($data['email']);
        $password = trim($data['password']);

        $useremail_exist = Users::where('email', $email)->first();

        // validate input fields
        if (!$this->csrf->verify($csrf_token)) {
            $errors['csrf_token'] = 'Invalid CSRF token. Please try again.';
        } else if (empty($email)) {
            $errors['email'] = 'Email Field is required';
        } else if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Please enter a valid email address';
        } else if (empty($password)) {
            $errors['email'] = 'Password Field is required';
        } else if (empty($useremail_exist)) {
            $errors['email'] = 'Specified Email not found';
        }

        if (!empty($errors)) {
            $token = $this->csrf->getToken();

            return $this->view->render($response, 'pages/login.twig', ['currentpage' => $current_page, 'toast' => $toast, 'errors' => $errors, 'old' => $data, 'csrf_token' => $token]);
        }

        if (empty($errors)) {
            $token = $this->csrf->getToken();

            // authentication user
            if ($useremail_exist['email'] == $email && password_verify($password, $useremail_exist['password'])) {
                $this->sessionhandler->setUserSession($useremail_exist, 'user');
                if ($remembermeinput) {
                    $this->rememberme->generateRemembermeToken($useremail_exist['userid']);
                }
                return Redirector::redirect_to('/dashboard');
            } else {
                $errors['autherror'] = 'Wrong Email or Password';
                return $this->view->render($response, 'pages/login.twig', ['currentpage' => $current_page, 'toast' => $toast, 'errors' => $errors, 'old' => $data, 'csrf_token' => $token]);
            }
        }
        return $this->view->render($response, 'pages/login.twig', ['currentpage' => $current_page]);
    }
}
