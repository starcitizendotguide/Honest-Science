<?php

return [
    'role_structure' => [
        'general' => [
            'profile'   => 'c,r,u,d',
            'managment' => 'r',
            'task'      => 'c,r,u,d'
        ],
        'recruiter' => [
            'profile'   => 'r,u',
            'task'      => 'r'
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
