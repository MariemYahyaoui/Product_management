<?php

namespace App\Service;

use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;

    public function __construct(string $appSecret)
    {
        $this->secret = $appSecret;
    }

    public function createToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'iat' => time(),
            'exp' => time() + 3600 * 24, // 24 hours
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decodeToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}
