/**
 * WordPress Integration Script for Bingo App
 * 
 * This script handles the integration between the Bingo app and WordPress
 */

(function($) {
    'use strict';
    
    // Main integration object
    var BingoWPIntegration = {
        // Initialize the integration
        init: function() {
            this.setupEventListeners();
            this.handleFullscreenRequests();
        },
        
        // Set up event listeners
        setupEventListeners: function() {
            // Listen for messages from the Bingo app iframe
            window.addEventListener('message', function(event) {
                // Check if the message is from our app
                if (event.data && typeof event.data === 'object') {
                    // Handle fullscreen toggle
                    if (event.data.action === 'toggleFullscreen') {
                        $(document).trigger('bingo-toggle-fullscreen');
                    }
                }
            }, false);
            
            // Add keyboard shortcut for exiting fullscreen (ESC key)
            $(document).keyup(function(e) {
                if (e.key === "Escape" && $('body').hasClass('bingo-fullscreen')) {
                    $(document).trigger('bingo-toggle-fullscreen');
                }
            });
        },
        
        // Handle fullscreen requests
        handleFullscreenRequests: function() {
            // Add click handler for manual fullscreen toggle
            $('.bingo-fullscreen-toggle').on('click', function(e) {
                e.preventDefault();
                $(document).trigger('bingo-toggle-fullscreen');
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        BingoWPIntegration.init();
    });
    
})(jQuery);