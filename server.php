<?php
/**
 * Simple PHP Server for Bingo App
 * 
 * This file provides a simple PHP server for the Bingo App
 * when not running within WordPress
 * 
 * Usage:
 * php -S localhost:8000 server.php
 */

// Define the root directory
$root = __DIR__;

// Get the requested URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// API endpoints routing
if (strpos($uri, '/api/') === 0) {
    // Forward to the Node.js server
    $nodejs_host = 'http://localhost:3001'; // Adjust this to the actual port of your Node.js server
    
    // Proxy the request to the Node.js server
    $ch = curl_init($nodejs_host . $uri);
    
    // Set CURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    
    // Set correct method
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $_SERVER['REQUEST_METHOD']);
    
    // Set headers
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'HTTP_') === 0) {
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[] = "$header: $value";
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    // Set POST/PUT data if available
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
        $content = file_get_contents('php://input');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    }
    
    // Execute the request
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Return the response
    http_response_code($status);
    echo $response;
    exit;
}

// Static files
$filepath = realpath($root . $uri);

// Check if file exists and is within the root directory
if ($filepath && is_file($filepath) && strpos($filepath, $root) === 0) {
    // Get the file extension
    $extension = pathinfo($filepath, PATHINFO_EXTENSION);
    
    // Set content type
    switch ($extension) {
        case 'css':
            $contentType = 'text/css';
            break;
        case 'js':
            $contentType = 'application/javascript';
            break;
        case 'json':
            $contentType = 'application/json';
            break;
        case 'png':
            $contentType = 'image/png';
            break;
        case 'jpg':
        case 'jpeg':
            $contentType = 'image/jpeg';
            break;
        case 'svg':
            $contentType = 'image/svg+xml';
            break;
        default:
            $contentType = 'text/html';
    }
    
    header("Content-Type: $contentType");
    readfile($filepath);
    exit;
}

// Default: serve the index.html file
include 'index.html';