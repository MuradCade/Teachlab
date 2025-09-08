<?php

namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Pagination\Paginator;

class Eloquent
{
    public static function setup(array $config): Capsule
    {

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $config['DB']['HOST'],
            'database'  => $config['DB']['DATABASE'],
            'username'  => $config['DB']['USERNAME'],
            'password'  => $config['DB']['PASSWORD'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);



        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        // Force the connection charset to utf8mb4 explicitly
        $capsule->getConnection()->statement("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        // 🔹 Pagination configuration (for Slim 4 / non-Laravel)
        Paginator::currentPathResolver(function () {
            $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $uri    = strtok($_SERVER['REQUEST_URI'], '?');
            return "{$scheme}://{$host}{$uri}";
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            return isset($_GET[$pageName]) && is_numeric($_GET[$pageName])
                ? (int) $_GET[$pageName]
                : 1;
        });

        return $capsule;
    }
}
