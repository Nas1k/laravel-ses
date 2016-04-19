<?php

return [
    'ses' => [
        'region' => env('AWS_REGION', 'us-west-2'),
        'version' => 'latest',
        'credentials' => [
            'key' => env('AWS_SES_KEY'),
            'secret' => env('AWS_SES_SECRET'),
        ],
    ],
    'sns' => [
        'region' => env('AWS_REGION', 'us-west-2'),
        'version' => 'latest',
        'credentials' => [
            'key' => env('AWS_SES_KEY'),
            'secret' => env('AWS_SES_SECRET'),
        ],
    ],
    'sqs' => [
        'region' => env('AWS_REGION', 'us-west-2'),
        'version' => 'latest',
        'credentials' => [
            'key' => env('AWS_SES_KEY'),
            'secret' => env('AWS_SES_SECRET'),
        ],
    ],
];
