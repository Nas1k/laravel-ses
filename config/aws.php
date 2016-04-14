<?php

return [
    'region' => env('AWS_REGION', 'us-west-2'),
    'version' => 'latest',
    'credentials' => [
        'key' => env('AWS_SES_KEY'),
        'secret' => env('AWS_SES_SECRET'),
    ],
];
