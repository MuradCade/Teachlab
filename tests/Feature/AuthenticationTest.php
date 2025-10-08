<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Controllers\Services\CsrfService;


it('validate csrf token', function () {
    $response = $this->request('POST', '/login', [
        // 'csrf_token' => (new CsrfService)->generateToken(),
        'csrf_token' => '',
        'email' => 'test@example.com',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();
    expect($body)->toContain('Invalid CSRF token. Please try again');
});

it('validate email field', function () {
    $csrf = new  CsrfService;
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/login', [
        'csrf_token' => $token,
        // 'csrf_token' => '',
        'email' => '',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();
    expect($body)->toContain('Email Field is required');
});


it('validate if the email is proper email', function () {
    $csrf = new  CsrfService;
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/login', [
        'csrf_token' => $token,
        'email' => 'hhh',
        'password' => 'Test#1212',
    ]);
    $body = (string) $response->getBody();
    expect($body)->toContain('Please enter a valid email address');
});

it('validate password field', function () {
    $csrf = new  CsrfService;
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/login', [
        'csrf_token' => $token,
        'email' => 'test@example.com',
        'password' => '',
    ]);
    $body = (string) $response->getBody();
    expect($body)->toContain('Password Field is required');
});


it('check if the email doesnt exist', function () {
    $csrf = new  CsrfService;
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/login', [
        'csrf_token' => $token,
        'email' => 'tests@example.com',
        'password' => 'Testst',
    ]);
    $body = (string) $response->getBody();
    // dd($body);
    expect($body)->toContain('Specified Email not found');
});


it('check redirecting to dashboard after successfull login', function () {
    $csrf = new  CsrfService;
    $token = $csrf->generateToken();
    $response = $this->request('POST', '/login', [
        'csrf_token' => $token,
        'email' => 'test@example.com',
        'password' => 'Test#1212',
    ]);
    expect($response->getStatusCode())->toBe(302); // typical redirect codes
    // dd($response->getStatusCode());
    expect($response->getHeaderLine('Location'))->toBe('/dashboard');
});

it('Logout user successfully', function () {
    $response = $this->request('GET', '/teacher/logout', []);

    expect($response->getStatusCode())->toBe(302); // typical redirect codes
    expect($response->getHeaderLine('Location'))->toBe('/login');
});
