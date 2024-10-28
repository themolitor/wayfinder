(function() {
    // Track if Wayfinder is enabled
    let wayfinderEnabled = true;
    
    // Listen for keydown
    document.addEventListener('keydown', function(e) {
        // Check for Alt+Shift+W using both key and code properties
        if (e.altKey && e.shiftKey && (
            e.key.toLowerCase() === 'w' || 
            e.code === 'KeyW' ||
            e.keyCode === 87 ||
            e.key === '„'  // Special case for some keyboard layouts
        )) {
            e.preventDefault();
            
            const stylesheet = document.querySelector('link[href*="editor-style.css"]');
            if (!stylesheet) return;
            
            wayfinderEnabled = !wayfinderEnabled;
            stylesheet.disabled = !wayfinderEnabled;
            
            // Remove any existing status message
            const existingStatus = document.getElementById('wayfinder-status');
            if (existingStatus) {
                existingStatus.remove();
            }
            
            // Create and append new status message
            const status = document.createElement('div');
            status.id = 'wayfinder-status';
            status.textContent = wayfinderEnabled ? 'Wayfinder: ON' : 'Wayfinder: OFF';
            status.style.background = wayfinderEnabled ? 'var(--wp-admin-theme-color)' : '#777';
            document.body.appendChild(status);
            
            // Force a reflow to ensure the transition works
            status.offsetHeight;
            
            // Fade out after 2 seconds
            setTimeout(() => {
                status.style.opacity = '0';
            }, 2000);
            
            // Remove from DOM after fade completes
            setTimeout(() => {
                status.remove();
            }, 2500);
        }
    });
    
    // Add the required CSS
    const style = document.createElement('style');
    style.textContent = `
        #wayfinder-status {
            position: fixed;
            bottom: 50px;
            left: 50%;
            transform: translate3d(-50%,0,0);
            color: white;
            padding: 10px 15px;
            border-radius: 3px;
            font-size: 15px;
            opacity: 1;
            transition: opacity 0.5s ease;
            z-index: 999999;
            box-shadow: 0 8px 16px rgba(0,0,0,.15);
        }
    `;
    document.head.appendChild(style);
})();