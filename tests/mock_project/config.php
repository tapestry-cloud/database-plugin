<?php return [
    'site' => [
        'url'         => 'http://localhost:3000',
    ],

    'kernel' => \Tests\MockProject\Kernel::class,

    'plugins' => [
        'database' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . DIRECTORY_SEPARATOR . 'db.sqlite'
        ]
    ]
];