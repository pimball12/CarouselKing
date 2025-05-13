<?php

namespace Src\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

class JWTManager
{
    private string $secret;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->secret = $_ENV['JWT_SECRET'] ?? throw new \Exception("JWT_SECRET não definido no .env");
    }

    public function encode(array $payload, int $exp = 3600): string
    {
        $payload['exp'] = time() + $exp;
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decode(string $token): array
    {
        $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
        return (array) $decoded;
    }

    public static function getBearerToken(): ?string
    {
        $headers = [];
    
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {

            $headers['Authorization'] = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {

            $requestHeaders = apache_request_headers();

            if (isset($requestHeaders['Authorization'])) {

                $headers['Authorization'] = $requestHeaders['Authorization'];
            } elseif (isset($requestHeaders['authorization'])) {
                
                $headers['Authorization'] = $requestHeaders['authorization'];
            }
        }
    
        if (!isset($headers['Authorization'])) {

            return null;
        }
    
        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            
            return $matches[1];
        }
    
        return null;
    }
}
