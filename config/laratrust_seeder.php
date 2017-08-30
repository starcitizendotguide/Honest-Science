<?php

return [
    'role_structure' => [
        'general' => [
            'profile'   => 'c,r,u,d',
            'managment' => 'r',
            'task'      => 'c,r,u,d',
            'child'     => 'c,r,u,d',
            'source'    => 'c,r,d',
            'user'      => 'c,r,u,d'
        ],
        'recruiter' => [
            'profile'   => 'r,u',
            'task'      => 'r',
            'child'     => 'r',
            'source'    => 'r',
            'user'      => ''
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
