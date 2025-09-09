<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'export/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'access-control-allow-origin' => [
        '*'
        // 'http://192.168.1.130',
        // 'http://192.168.1.130:8080',
        // 'http://192.168.1.130:8082',
        // 'http://localhost',
        // 'http://localhost:8080',
        // 'http://localhost:8082',
        // 'http://192.168.1.98:8280',
        // 'http://192.168.1.6:8080',
        // 'http://192.168.1.6:8082',
        // 'http://192.168.1.6',
        // 'http://192.168.1.11:8080',
        // 'http://192.168.1.11:8082',
        // 'http://192.168.1.11',
      	// 'http://62.182.31.140:18080',
        // 'http://192.168.1.98',
        // 'http://192.168.1.98:8280',
	      // 'http://62.182.31.140',
        // 'http://192.168.1.98',
        // 'sockjs-eu.pusher.com',
        // 'http://sockjs-eu.pusher.com',
        // 'https://sockjs-eu.pusher.com',
        // 'https://192.168.1.98',
    ],

    'max_age' => 0,

    'supports_credentials' => true,

    'trusted' => [
      '*'
        // 'http://192.168.1.130',
        // 'http://192.168.1.130:8080',
        // 'http://192.168.1.130:8082',
        // 'http://localhost',
        // 'http://localhost:8080',
        // 'http://localhost:8082',
        // 'http://192.168.1.98:8280',
        // 'http://192.168.1.6:8080',
        // 'http://192.168.1.6:8082',
        // 'http://192.168.1.6',
        // 'https://192.168.1.98',
        // 'http://192.168.1.11:8080',
        // 'http://192.168.1.11:8082',
        // 'http://192.168.1.11',
      	// 'http://62.182.31.140:18080',
        // 'http://192.168.1.98',
        // 'http://192.168.1.98:8280',
	      // 'http://62.182.31.140',
        // 'sockjs-eu.pusher.com',
        // 'http://sockjs-eu.pusher.com',
        // 'https://sockjs-eu.pusher.com'
    ],
];
