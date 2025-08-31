<?php
/**
 * Configuration Example
 * 
 * Copy this file to config.php and update with your actual credentials
 */

return [
    'angel_one' => [
        'client_code' => 'YOUR_CLIENT_CODE',    // Your Angel One client code (e.g., 'K123456')
        'password' => 'YOUR_PASSWORD',          // Your Angel One login password
        'api_key' => 'YOUR_API_KEY',           // Your Angel One API key from developer portal
    ],
    
    // Optional: Default request settings
    'defaults' => [
        'timeout' => 30,                        // Request timeout in seconds
        'retry_attempts' => 3,                  // Number of retry attempts on failure
    ],
    
    // Optional: Logging settings
    'logging' => [
        'enabled' => false,                     // Enable/disable logging
        'log_file' => 'angel_api.log',         // Log file path
        'log_level' => 'INFO',                 // Log level (DEBUG, INFO, WARNING, ERROR)
    ]
];