<?php

return [

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        // Custom disk for PDFs stored in public_html/sales
        'public_html_sales' => [
            'driver' => 'local',
            'root' => '/home/u121027207/public_html/sales/storage/app/public/pdf',  // Absolute path on the server
            'url' => env('APP_URL') . '/sales/storage/app/public/pdf',  // Publicly accessible URL path
            'visibility' => 'public',
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],
    ],

    // Symlink for public directory
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
