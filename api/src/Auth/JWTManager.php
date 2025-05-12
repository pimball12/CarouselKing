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
}
