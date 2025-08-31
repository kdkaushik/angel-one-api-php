<?php
/**
 * Angel One API PHP Client
 * 
 * A PHP client library for Angel One (Angel Broking) API integration
 * Supports authentication, trading operations, market data, and portfolio management
 * 
 * @author Keshav D Kaushik
 * @version 1.0.0
 * @license MIT
 * @requires PHP 8.2+
 */

class AngelOneAPI {
    private $clientCode;
    private $password;
    private $apiKey;
    private $jwtToken;
    private $refreshToken;
    private $feedToken;
    
    const BASE_URL = 'https://apiconnect.angelone.in';
    
    public function __construct($clientCode, $password, $apiKey) {
        $this->clientCode = $clientCode;
        $this->password = $password;
        $this->apiKey = $apiKey;
    }
    
    /**
     * Login with TOTP and get authentication tokens
     * 
     * @param string $totp 6-digit TOTP from authenticator app
     * @return array Login response with tokens
     * @throws Exception on login failure
     */
    public function login($totp) {
        $totp = preg_replace('/\D/', '', $totp);
        $url = self::BASE_URL . '/rest/auth/angelbroking/user/v1/loginByPassword';
        
        $data = [
            'clientcode' => $this->clientCode,
            'password' => $this->password,
            'totp' => $totp
        ];
        
        $response = $this->makeRequest($url, 'POST', $data, $this->getLoginHeaders());
        
        if (!$response || empty($response['status'])) {
            throw new Exception($response['message'] ?? 'Login failed');
        }
        
        $this->jwtToken = $response['data']['jwtToken'];
        $this->refreshToken = $response['data']['refreshToken'];
        $this->feedToken = $response['data']['feedToken'] ?? null;
        
        return $response['data'];
    }
    
    /**
     * Get Last Traded Price (LTP) for instruments
     * 
     * @param array $params Parameters with exchange, tradingsymbol, symboltoken
     * @return float LTP value
     */
    public function getLTP($params) {
        $url = self::BASE_URL . '/rest/secure/angelbroking/order/v1/getLtpData';
        $response = $this->makeRequest($url, 'POST', $params, $this->getAuthHeaders());
        
        if ($response && $response['message'] === 'SUCCESS') {
            return $response['data']['ltp'] ?? 0;
        }
        
        throw new Exception($response['message'] ?? 'Failed to fetch LTP data');
    }
    
    /**
     * Get historical candle data
     * 
     * @param array $params Parameters with exchange, symboltoken, interval, fromdate, todate
     * @return array Candle data
     */
    public function getCandleData($params) {
        $url = self::BASE_URL . '/rest/secure/angelbroking/historical/v1/getCandleData';
        $response = $this->makeRequest($url, 'POST', $params, $this->getAuthHeaders());
        
        if ($response && $response['message'] === 'SUCCESS') {
            return $response['data'] ?? [];
        }
        
        throw new Exception($response['message'] ?? 'Failed to fetch candle data');
    }
    
    /**
     * Get user profile information
     * 
     * @return array Profile data
     */
    public function getProfile() {
        $url = self::BASE_URL . '/rest/secure/angelbroking/v1/getProfile';
        $response = $this->makeRequest($url, 'GET', [], $this->getAuthHeaders());
        
        return $response['data'] ?? null;
    }
    
    /**
     * Get current JWT token
     * 
     * @return string|null JWT token
     */
    public function getJwtToken() {
        return $this->jwtToken;
    }
    
    /**
     * Make HTTP request to Angel One API
     * 
     * @param string $url API endpoint URL
     * @param string $method HTTP method (GET, POST)
     * @param array $data Request data
     * @param array $headers HTTP headers
     * @return array Response data
     * @throws Exception on request failure
     */
    private function makeRequest($url, $method = 'POST', $data = [], $headers = []) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        
        if (!empty($data) && ($method === 'POST' || $method === 'PUT')) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false || $httpCode !== 200) {
            throw new Exception("HTTP Error: {$httpCode}");
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Get headers for login requests
     * 
     * @return array Headers
     */
    private function getLoginHeaders() {
        return [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-UserType: USER',
            'X-SourceID: WEB',
            'X-ClientLocalIP: 192.168.1.1',
            'X-ClientPublicIP: 106.193.147.98',
            'X-MACAddress: fe80::216e:6507:4b90:3719',
            'X-PrivateKey: ' . $this->apiKey
        ];
    }
    
    /**
     * Get headers for authenticated requests
     * 
     * @return array Headers
     */
    private function getAuthHeaders() {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-UserType: USER',
            'X-SourceID: WEB',
            'X-ClientLocalIP: 192.168.1.1',
            'X-MACAddress: fe80::216e:6507:4b90:3719',
            'X-PrivateKey: ' . $this->apiKey,
        ];
        
        if ($this->jwtToken) {
            $headers[] = 'Authorization: Bearer ' . $this->jwtToken;
        }
        
        return $headers;
    }
}