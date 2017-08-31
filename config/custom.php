<?php

return [
    'auth'      => [
        'login'     => env('AUTH_LOGIN'),
        'register'  => env('AUTH_REGISTER'),
        'reset'     => env('AUTH_RESET')
    ],
    'permission' => [
        'default_user' => env('PERMISSION_DEFAULT_USER')
    ],
    'disqus'    => [
        'shortname' => env('DISQUS_SHORTNAME')
    ]
];
