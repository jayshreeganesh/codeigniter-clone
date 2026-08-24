<?php
/**
 * CodeIgniter Clone - Front Controller
 * Zero dependency / Ultra-low Inode MVC Application
 */

define('ENVIRONMENT', 'development');

// Path to the application folder
define('APPPATH', __DIR__ . '/application/');

// Path to the system core folder
define('BASEPATH', __DIR__ . '/system/');

// Load System Core Engine
require_once BASEPATH . 'core/CodeIgniter.php';

// Bootstrap and process request
CodeIgniter::run();
