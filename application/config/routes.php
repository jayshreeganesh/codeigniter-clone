<?php
/**
 * Application Routes
 */
return [
    'default_controller' => 'products',
    '404_override'       => '',

    // Custom URI Routes
    'products'           => 'products/index',
    'products/create'    => 'products/create',
    'products/store'     => 'products/store',
    'products/view/(:num)' => 'products/view/$1',
    'products/edit/(:num)' => 'products/edit/$1',
    'products/update/(:num)' => 'products/update/$1',
    'products/delete/(:num)' => 'products/delete/$1',
];
