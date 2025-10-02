<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

abstract class TestCase extends BaseTestCase
{
    protected $app;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app = require __DIR__ . '/../bootstrap/app.php';
    }

    protected function request(string $method, string $uri, array $data = []): \Psr\Http\Message\ResponseInterface
    {
        $factory = new ServerRequestFactory();
        $request = $factory->createServerRequest($method, $uri);

        if (!empty($data)) {
            $request = $request->withParsedBody($data);
        }

        return $this->app->handle($request);
    }
}
