<?php
/**
 * Database Configuration
 * Set 'driver' to 'sqlite' for zero-configuration, or 'mysql' for live cPanel/InfinityFree/Aeon.
 */
return [
    'driver'       => 'sqlite', // Options: 'sqlite' or 'mysql'
    
    // SQLite settings
    'sqlite_path'  => __DIR__ . '/../database.sqlite',

    // MySQL settings (for InfinityFree / Aeon / cPanel)
    'hostname'     => '127.0.0.1',
    'port'         => 3306,
    'username'     => 'root',
    'password'     => '',
    'database'     => 'ci_crud',
];
