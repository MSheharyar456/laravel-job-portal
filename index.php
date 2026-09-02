<?php

/**
 * Laravel Application Entry Point - TST Subdomain
 * 
 * This file redirects all requests to the Laravel public directory.
 * It serves as a fallback if .htaccess redirection doesn't work.
 */

// Define the path to the Laravel public directory
$publicPath = __DIR__ . '/laravel12/public';

// Get the request URI
$uri = $_SERVER['REQUEST_URI'];

// Remove the subdomain path if present
$uri = preg_replace('#^/+#', '', $uri);

// Check if requesting a specific file in public directory
$requestedFile = $publicPath . '/' . $uri;

// If it's a real file (css, js, images), serve it directly
if ($uri && file_exists($requestedFile) && is_file($requestedFile)) {
    // Get the file extension
    $extension = pathinfo($requestedFile, PATHINFO_EXTENSION);
    
    // Set appropriate content type
    $contentTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];
    
    if (isset($contentTypes[$extension])) {
        header('Content-Type: ' . $contentTypes[$extension]);
    }
    
    readfile($requestedFile);
    exit;
}

// Otherwise, load Laravel's index.php
require_once $publicPath . '/index.php';
