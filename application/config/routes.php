<?php
/**
 * Application Routes
 */
return [
    'default_controller' => 'products',
    '404_override'       => '',

    'login'              => 'auth/login',
    'register'           => 'auth/register',
    'logout'             => 'auth/logout',

    'products'           => 'products/index',
    'products/export'    => 'products/export',
    'products/create'    => 'products/create',
    'products/store'     => 'products/store',
    'products/toggle_active/(:num)' => 'products/toggle_active/$1',
    'products/view/(:num)' => 'products/view/$1',
    'products/edit/(:num)' => 'products/edit/$1',
    'products/update/(:num)' => 'products/update/$1',
    'products/delete/(:num)' => 'products/delete/$1',
    'products/force_delete/(:num)' => 'products/force_delete/$1',
    'products/restore/(:num)' => 'products/restore/$1',
];
