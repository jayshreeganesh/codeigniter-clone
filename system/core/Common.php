<?php
/**
 * CodeIgniter Clone Common Helpers
 */

if (!function_exists('get_instance')) {
    function &get_instance(): CI_Controller {
        return CI_Controller::get_instance();
    }
}

if (!function_exists('base_url')) {
    function base_url(string $uri = ''): string {
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        $base = ($scriptDir === '/' || $scriptDir === '\\') ? '' : rtrim($scriptDir, '/');
        return $base . '/' . ltrim($uri, '/');
    }
}

if (!function_exists('site_url')) {
    function site_url(string $uri = ''): string {
        return base_url($uri);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $uri = '') {
        $url = (str_starts_with($uri, 'http://') || str_starts_with($uri, 'https://')) ? $uri : site_url($uri);
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('set_flashdata')) {
    function set_flashdata(string $key, $value) {
        $ci = &get_instance();
        $ci->session->set_flashdata($key, $value);
    }
}

if (!function_exists('flashdata')) {
    function flashdata(string $key = null) {
        $ci = &get_instance();
        return $ci->session->flashdata($key);
    }
}
