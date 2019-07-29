<?php

return [
    'url' => env('KOALABOOX_API_URL', 'http://connect.koalaboox.lan'),
    'app' => [
        'id' => env('KOALABOOX_API_APP_ID'),
        'secret' => env('KOALABOOX_API_APP_SECRET'),
    ],
    'curl' => [
        CURLOPT_SSL_VERIFYPEER => false,
    ],
];
