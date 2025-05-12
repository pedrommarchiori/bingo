<?php
/**
 * Bingo: Festa da Padroeira - Web Application
 * 
 * This file serves as an entry point for the Bingo application
 * which can be embedded in WordPress or run standalone.
 */

// Prevent direct access to this file unless from WordPress
$is_wordpress = defined('ABSPATH');

// If this is running outside of WordPress, provide the necessary HTML wrapper
if (!$is_wordpress) {
    header('Content-Type: text/html; charset=utf-8');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo: Festa da Padroeira</title>
    <meta name="description" content="Sistema de bingo para festas da padroeira com gerenciador de eventos, gerador de cartelas e sorteio online.">
    
    <?php if (!$is_wordpress): ?>
    <!-- Custom styles for standalone mode -->
    <style>
        /* Reset for standalone mode */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lato', sans-serif;
        }
        .bingo-app-container {
            width: 100%;
            min-height: 100vh;
        }
    </style>
    <?php else: ?>
    <!-- WordPress mode styles -->
    <style>
        .bingo-app-container {
            width: 100%;
            min-height: 700px;
            margin: 20px 0;
        }
        /* Hide some WordPress elements when in fullscreen mode */
        body.bingo-fullscreen #wpadminbar,
        body.bingo-fullscreen #masthead,
        body.bingo-fullscreen #colophon,
        body.bingo-fullscreen .site-header,
        body.bingo-fullscreen .site-footer,
        body.bingo-fullscreen aside,
        body.bingo-fullscreen .sidebar {
            display: none !important;
        }
        body.bingo-fullscreen .bingo-app-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
        }
    </style>
    <?php endif; ?>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Lato:wght@400;700&family=Playfair+Display&display=swap" rel="stylesheet">
</head>
<body<?php echo $is_wordpress ? ' class="bingo-wordpress-mode"' : ''; ?>>
    <?php if (!$is_wordpress): ?>
    <div class="bingo-app-container">
        <div id="root"></div>
    </div>
    <?php else: ?>
    <div class="bingo-app-container">
        <div id="root"></div>
    </div>
    <script>
        // Helper function for toggling fullscreen in WordPress
        function toggleBingoFullscreen() {
            document.body.classList.toggle('bingo-fullscreen');
        }
        
        // Listen for fullscreen events from the app
        window.addEventListener('message', function(event) {
            if (event.data && event.data.action === 'toggleFullscreen') {
                toggleBingoFullscreen();
            }
        }, false);
    </script>
    <?php endif; ?>
    
    <!-- Load application assets -->
    <script type="module" src="./client/dist/assets/index.js"></script>
    <link rel="stylesheet" href="./client/dist/assets/index.css">

    <?php if ($is_wordpress): ?>
    <script>
        // Initialize any WordPress specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Bingo app initialized in WordPress mode');
        });
    </script>
    <?php endif; ?>
</body>
</html>