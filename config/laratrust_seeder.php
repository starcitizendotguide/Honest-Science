<?php

return [
    'role_structure' => [
        'general' => [
            'profile'   => 'c,r,u,d',
            'managment' => 'r',
            'task'      => 'c,r,u,d,v,m',
            'child'     => 'c,r,u,d,v,m',
            'source'    => 'c,r,d',
            'user'      => 'c,r,u,d',
            'visibility'=> 'b',
            'log'       => 'r',
        ],
        'contributor' => [
            'profile'   => 'r,u',
            'managment' => 'r',
            'task'      => 'c,r,u',
            'child'     => 'c,r,u',
            'source'    => 'c,r',
            'visibility'=> 'b',
        ],
        'recruit' => [
            'profile'   => 'r,u',
            'task'      => 'r',
            'child'     => 'r',
            'source'    => 'r',
        ],
    ],
    'permission_structure' => [],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'v' => 'mark-as-verified',
        'm' => 'mark-as-updated',
        'b' => 'bypass',
    ]
];
