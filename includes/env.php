<?php
/**
 * Simple .env file loader
 * Loads key=value pairs from a .env file into $_ENV
 * No external dependencies needed (no Composer)
 */
function load_env($path) {
    if (!file_exists($path)) {
        error_log('.env file not found at: ' . $path);
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip empty lines and comments
        if ($line === '' || $line[0] === '#') continue;
        
        // Split on first = sign
        $eqPos = strpos($line, '=');
        if ($eqPos === false) continue;
        
        $key   = trim(substr($line, 0, $eqPos));
        $value = trim(substr($line, $eqPos + 1));
        
        // Remove surrounding quotes if present
        if (strlen($value) >= 2) {
            if (($value[0] === '"' && $value[strlen($value)-1] === '"') ||
                ($value[0] === "'" && $value[strlen($value)-1] === "'")) {
                $value = substr($value, 1, -1);
            }
        }
        
        $_ENV[$key] = $value;
        // Also set in putenv for compatibility
        putenv("{$key}={$value}");
    }
}
