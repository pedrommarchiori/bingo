<?php
/**
 * Asset Proxy for Bingo App
 * 
 * This script provides a way to load assets from the Vite build directory
 * when used within WordPress or as a standalone PHP application.
 */

// Define base paths
$publicPath = './dist/public/';
$requestedFile = isset($_GET['file']) ? $_GET['file'] : '';

// Sanitize the file path to prevent directory traversal
$requestedFile = str_replace('..', '', $requestedFile);

// Determine the file path
$filePath = $publicPath . $requestedFile;

// Check if the file exists
if (file_exists($filePath) && is_file($filePath)) {
    // Get file extension
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    
    // Set appropriate content type
    switch ($extension) {
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'json':
            header('Content-Type: application/json');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
        case 'woff':
            header('Content-Type: font/woff');
            break;
        case 'woff2':
            header('Content-Type: font/woff2');
            break;
        case 'ttf':
            header('Content-Type: font/ttf');
            break;
        default:
            header('Content-Type: application/octet-stream');
    }
    
    // Output file contents
    readfile($filePath);
} else {
    // Return 404 if file not found
    header("HTTP/1.0 404 Not Found");
    echo "File not found";
}