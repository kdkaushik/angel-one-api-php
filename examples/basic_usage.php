<?php
/**
 * Basic Usage Example - Angel One API PHP Client
 * 
 * This example demonstrates:
 * - Login with TOTP
 * - Get Last Traded Price (LTP)
 * - Get user profile
 * - Basic error handling
 */

require_once '../src/AngelOneAPI.php';

// Configuration - Replace with your actual credentials
$config = [
    'client_code' => 'YOUR_CLIENT_CODE',    // Your Angel One client code
    'password' => 'YOUR_PASSWORD',          // Your Angel One password
    'api_key' => 'YOUR_API_KEY'            // Your Angel One API key
];

// Initialize API client
$api = new AngelOneAPI(
    $config['client_code'],
    $config['password'],
    $config['api_key']
);

echo "=== Angel One API Basic Usage Example ===\n\n";

try {
    // Step 1: Login with TOTP
    echo "1. Logging in...\n";
    $totp = readline("Enter your 6-digit TOTP: ");
    
    $loginData = $api->login($totp);
    echo "✅ Login successful!\n";
    echo "   JWT Token: " . substr($api->getJwtToken(), 0, 20) . "...\n\n";
    
    // Step 2: Get user profile
    echo "2. Getting user profile...\n";
    $profile = $api->getProfile();
    
    if ($profile) {
        echo "✅ Profile retrieved:\n";
        echo "   Name: " . ($profile['name'] ?? 'N/A') . "\n";
        echo "   Client Code: " . ($profile['clientcode'] ?? 'N/A') . "\n";
        echo "   Email: " . ($profile['email'] ?? 'N/A') . "\n\n";
    }
    
    // Step 3: Get LTP for NIFTY 50
    echo "3. Getting NIFTY 50 LTP...\n";
    $ltpParams = [
        'exchange' => 'NSE',
        'tradingsymbol' => 'NIFTY 50',
        'symboltoken' => '99926000'  // NIFTY 50 token
    ];
    
    $ltp = $api->getLTP($ltpParams);
    echo "✅ NIFTY 50 LTP: ₹" . number_format($ltp, 2) . "\n\n";
    
    // Step 4: Get LTP for a stock (SBIN)
    echo "4. Getting SBIN LTP...\n";
    $sbinParams = [
        'exchange' => 'NSE',
        'tradingsymbol' => 'SBIN-EQ',
        'symboltoken' => '3045'  // SBIN token
    ];
    
    $sbinLtp = $api->getLTP($sbinParams);
    echo "✅ SBIN LTP: ₹" . number_format($sbinLtp, 2) . "\n\n";
    
    // Step 5: Get historical candle data
    echo "5. Getting historical candle data for SBIN...\n";
    $candleParams = [
        'exchange' => 'NSE',
        'symboltoken' => '3045',
        'interval' => 'ONE_MINUTE',
        'fromdate' => date('Y-m-d 09:15', strtotime('-1 day')),
        'todate' => date('Y-m-d 15:30', strtotime('-1 day'))
    ];
    
    $candles = $api->getCandleData($candleParams);
    
    if ($candles && count($candles) > 0) {
        echo "✅ Retrieved " . count($candles) . " candles\n";
        echo "   First candle: " . json_encode($candles[0]) . "\n";
        echo "   Last candle: " . json_encode(end($candles)) . "\n\n";
    } else {
        echo "⚠️  No candle data available\n\n";
    }
    
    echo "🎉 All operations completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting tips:\n";
    echo "- Check your credentials in the config array\n";
    echo "- Ensure TOTP is correct and not expired\n";
    echo "- Verify your Angel One account has API access enabled\n";
    echo "- Check your internet connection\n";
}

echo "\n=== Example completed ===\n";