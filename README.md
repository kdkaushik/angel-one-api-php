# Angel One API PHP Client

A lightweight PHP client library for Angel One (Angel Broking) API integration. This library provides easy access to Angel One's trading APIs including authentication, market data, portfolio management, and trading operations.

## Features

- 🔐 **Authentication**: Login with TOTP and token management
- 📊 **Market Data**: Real-time LTP, historical candle data, Open Interest
- 💼 **Portfolio**: Get holdings, positions, order book, trade book
- 🔄 **Token Management**: Automatic token refresh and validation
- ⚡ **Lightweight**: Minimal dependencies, pure PHP implementation
- 🛡️ **Error Handling**: Comprehensive exception handling

## Requirements

- PHP 8.2 or higher
- cURL extension enabled
- Angel One trading account with API access

## Installation

### Manual Installation

1. Download the `AngelOneAPI.php` file
2. Include it in your project:

```php
require_once 'path/to/AngelOneAPI.php';
```

### Composer Installation (Future)

```bash
composer require your-username/angel-one-api-php
```

## Quick Start

### 1. Basic Setup

```php
<?php
require_once 'src/AngelOneAPI.php';

// Initialize with your credentials
$api = new AngelOneAPI(
    'YOUR_CLIENT_CODE',    // Angel One client code
    'YOUR_PASSWORD',       // Angel One password
    'YOUR_API_KEY'         // Angel One API key
);
```

### 2. Login with TOTP

```php
try {
    // Get TOTP from your authenticator app
    $totp = '123456'; // 6-digit TOTP
    
    $loginData = $api->login($totp);
    echo "Login successful!\n";
    echo "JWT Token: " . $api->getJwtToken() . "\n";
    
} catch (Exception $e) {
    echo "Login failed: " . $e->getMessage() . "\n";
}
```

### 3. Get Market Data

```php
// Get Last Traded Price (LTP)
try {
    $ltpParams = [
        'exchange' => 'NSE',
        'tradingsymbol' => 'SBIN-EQ',
        'symboltoken' => '3045'
    ];
    
    $ltp = $api->getLTP($ltpParams);
    echo "SBIN LTP: ₹" . $ltp . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### 4. Get Historical Data

```php
// Get candle data
try {
    $candleParams = [
        'exchange' => 'NSE',
        'symboltoken' => '3045',
        'interval' => 'ONE_MINUTE',
        'fromdate' => '2024-01-15 09:15',
        'todate' => '2024-01-15 15:30'
    ];
    
    $candles = $api->getCandleData($candleParams);
    echo "Retrieved " . count($candles) . " candles\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### 5. Get Portfolio Information

```php
// Get user profile
try {
    $profile = $api->getProfile();
    echo "User: " . $profile['name'] . "\n";
    echo "Client Code: " . $profile['clientcode'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## API Methods

### Authentication Methods

| Method | Description | Parameters |
|--------|-------------|------------|
| `login($totp)` | Login with TOTP | `$totp` - 6-digit TOTP |
| `getJwtToken()` | Get current JWT token | None |

### Market Data Methods

| Method | Description | Parameters |
|--------|-------------|------------|
| `getLTP($params)` | Get Last Traded Price | `exchange`, `tradingsymbol`, `symboltoken` |
| `getCandleData($params)` | Get historical candle data | `exchange`, `symboltoken`, `interval`, `fromdate`, `todate` |

### Portfolio Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `getProfile()` | Get user profile | User profile data |
| `getOrderBook()` | Get order book | List of orders |
| `getTradeBook()` | Get trade book | List of trades |
| `getHoldings()` | Get holdings | List of holdings |
| `getPositions()` | Get positions | List of positions |

## Configuration

### API Credentials

You need the following credentials from Angel One:

1. **Client Code**: Your Angel One client ID
2. **Password**: Your Angel One login password  
3. **API Key**: Generate from Angel One developer portal
4. **TOTP**: Enable 2FA and use authenticator app

### Getting API Access

1. Login to [Angel One Developer Portal](https://smartapi.angelone.in/)
2. Create a new app to get API credentials
3. Enable TOTP authentication in your Angel One account
4. Use the generated API key in your application

## Error Handling

The library throws exceptions for various error conditions:

```php
try {
    $api->login($totp);
} catch (Exception $e) {
    switch ($e->getMessage()) {
        case 'Invalid TOTP':
            // Handle invalid TOTP
            break;
        case 'Login failed':
            // Handle login failure
            break;
        default:
            // Handle other errors
            echo "Error: " . $e->getMessage();
    }
}
```

## Rate Limiting

Angel One API has rate limits:
- **Login**: 1 request per second
- **Market Data**: 3 requests per second
- **Orders**: 10 requests per second

Implement appropriate delays between requests to avoid rate limiting.

## Examples

See the `examples/` directory for complete working examples:

- `basic_usage.php` - Basic login and market data
- `portfolio_example.php` - Portfolio management
- `historical_data.php` - Historical data retrieval

## Testing

Tested with:
- PHP 8.2+
- Angel One API v1
- Windows/Linux environments

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Disclaimer

This library is for educational and development purposes. Use at your own risk. The authors are not responsible for any financial losses incurred through the use of this library.

## Support

- 📧 Email: kdkaushik@gmail.com
- 🐛 Issues: [GitHub Issues](https://github.com/kdkaushik/angel-one-api-php/issues)
- 📖 Documentation: [Wiki](https://github.com/kdkaushik/angel-one-api-php/wiki)

## Changelog

### v1.0.0 (2024-01-15)
- Initial release
- Basic authentication and market data support
- Portfolio management methods
- Error handling and documentation