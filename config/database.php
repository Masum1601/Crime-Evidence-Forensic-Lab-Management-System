<?php

use Illuminate\Support\Str;

return [

    'default' => env('DB_CONNECTION', 'oracle'),

    'connections' => [

        // Oracle connection — Crime Evidence & Forensic Lab Management System
        // Driver provided by yajra/laravel-oci8
        'oracle' => [
            'driver'         => 'oracle',
            'tns'            => env('DB_TNS', ''),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '1521'),
            'database'       => env('DB_DATABASE', 'XE'),
            'service_name'   => env('DB_SERVICE_NAME', env('DB_DATABASE', 'XE')),
            'username'       => env('DB_USERNAME', 'cefl_user'),
            'password'       => env('DB_PASSWORD', ''),
            'charset'        => env('DB_CHARSET', 'AL32UTF8'),
            'prefix'         => '',
            'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
            'edition'        => env('DB_EDITION', 'ora$base'),
            'server_version' => env('DB_SERVER_VERSION', '11g'),
            'load_balance'   => env('DB_LOAD_BALANCE', 'yes'),
        ],

        // Fallback: MySQL (handy if Oracle isn't reachable during early dev/demo)
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],
    ],

];
