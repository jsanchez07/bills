<?php
// Session initialization file - include this at the top of all PHP files

// Only start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    // Set session parameters BEFORE starting session
    ini_set('session.cookie_domain', '');
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.use_cookies', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 0);
    ini_set('session.cookie_secure', 0);
    ini_set('session.gc_maxlifetime', 1440);
    
    // Use a specific directory for session files
    $session_dir = '/tmp/php_sessions';
    if (!is_dir($session_dir)) {
        mkdir($session_dir, 0777, true);
    }
    ini_set('session.save_path', $session_dir);
    
    // Start session
    session_start();
}

// Force session write at the end of script execution
register_shutdown_function(function() {
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_write_close();
    }
});
?>
