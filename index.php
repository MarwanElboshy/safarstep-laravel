<?php
/**
 * Fallback front controller for LiteSpeed with subfolder deployment.
 * Strips the /v2 prefix and routes traffic to public/index.php.
 */

// Strip /v2 prefix from REQUEST_URI so Laravel sees the correct path
$_SERVER['REQUEST_URI'] = preg_replace('|^/v2(?=/)|', '', $_SERVER['REQUEST_URI'] ?: '/');
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';

define('LARAVEL_START', microtime(true));

// Change to the public directory to ensure relative paths work correctly
chdir(__DIR__ . '/public');

// Require the public/index.php with the adjusted request context
require __DIR__ . '/public/index.php';
