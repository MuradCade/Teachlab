<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Controllers\Services\CsrfService;
use App\Models\MailQueueu;
use App\Models\Users;
use App\Models\UserSubscription;
use App\Models\VerifyEmail;

//----------------------------------------

//    Create Account Testing

//----------------------------------------



// input validations
it('validate csrf token', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        // 'csrf_token' => (new CsrfService)->generateToken(),
        'csrf_token' => '',
        'fullname' => 'ww',
        'email' => 'test@example.com',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();
    // echo $body;
    // allchecks
    expect($body)->toContain('Invalid CSRF token. Please try again');
});


it('fullname field validation', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => '',
        'email' => 'test@example.com',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();
    // echo $body;
    expect($body)->toContain('Fullname Field is required');
});


it('checking if email is empty', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => '',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();
    // echo $body;

    expect($body)->toContain('Email Field is required');
});


it('checking if email is valid/proper email', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => 'tetet',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();

    expect($body)->toContain('Please enter a valid email address');
});

it('checking if email is already taken or not', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => 'test@example.com',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();

    expect($body)->toContain('Email already exists. Please use a different email.');
});



it('checking if password is empty', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => 'test12@example.com',
        'password' => '',
    ]);
    $body = (string) $response->getBody();

    expect($body)->toContain('Password Field is required');
});


it('checking if password icontains uppercase , lowercase ,symbol , numbers', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => 'test12@example.com',
        'password' => 'test',
    ]);
    $body = (string) $response->getBody();
    expect($body)->toContain('Password must be at least 6 characters, Password must contain uppercase letter, lowercase letter,number, and symbol.');
});







// ===================================================
// Database Testing (related to registration)
// ====================================================

it('check if data is been stored in the users model', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $email = 'test' . rand(1000, 9999) . '@example.com';
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => $email,
        'password' => 'Test#122',
    ]);

    $user = Users::where('email', $email)->first();
    expect($user)->not()->toBeNull();
    expect($user->fullname)->toBe('test'); // Check fullname
    expect($user->email)->toBe($email);         // Check email
    expect(password_verify('Test#122', $user->password))->toBeTrue(); // Check password

});



it('check if user id found in user_subscription', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $email = 'test@example.com';
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => $email,
        'password' => 'Test#1212',
    ]);

    $user = Users::where('email', $email)->first();
    $user_subscription = UserSubscription::where('userid', $user->userid)->first();
    expect($user_subscription)->not()->toBeNull();
});


it('check if user id and email token found verifyemail', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $email = 'test' . rand(1000, 9999) . '@example.com';
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => $email,
        'password' => 'Test#122',
    ]);

    $user = Users::where('email', $email)->first();
    $verifyemail = VerifyEmail::where('userid', $user->userid)->first();
    expect($verifyemail)->not()->toBeNull();
    expect($verifyemail->userid)->toBe($user->userid);
    expect($verifyemail->token)->not()->toBeNull();
    expect($verifyemail->is_used)->toBe(0);
    // echo $response;
    // $user = Users::create([]);
});


it('check if mail is been qeued', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $email = 'test' . rand(1000, 9999) . '@example.com';
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => $email,
        'password' => 'Test#122',
    ]);

    $user = Users::where('email', $email)->first();
    $mailqueue = MailQueueu::where('userid', $user->userid)->first();
    expect($mailqueue)->not()->toBeNull();
    expect($mailqueue->userid)->toBe($user->userid);
    expect($mailqueue->username)->toBe($user->fullname);
    expect($mailqueue->useremail)->toBe($user->email);
    expect($mailqueue->mail_type)->toBe('signup');
    expect($mailqueue->mail_status)->toBe('pending');
    // echo $response;
    // $user = Users::create([]);
});


it('check for redirection to congrats page', function () {
    $csrf = new CsrfService();
    $token = $csrf->generateToken();
    $email = 'test' . rand(1000, 9999) . '@example.com';
    $response = $this->request('POST', '/create_account', [
        'csrf_token' => $token,
        'fullname' => 'test',
        'email' => $email,
        'password' => 'Test#122',
    ]);

    // --- New: check redirection ---
    expect($response->getStatusCode())->toBe(302); // typical redirect codes
    expect($response->getHeaderLine('Location'))->toBe('/congratulations'); // adjust URL as needed
    // echo $response;
    // $user = Users::create([]);
});
