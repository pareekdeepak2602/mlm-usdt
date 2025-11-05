<?php

namespace App\Utils;

class HmacGenerator
{
    /**
     * Generate HMAC compatible with Node.js verifyHMAC middleware
     *
     * @param array $data      The request body (as associative array)
     * @param string $secret   Your API secret key
     * @param int|string $timestamp  Millisecond timestamp
     * @return string          Hex-encoded HMAC SHA256 signature
     */
    public static function generateSignature(array $data, string $secret, $timestamp)
    {
        // Normalize JSON like Node.js (no extra spaces or escaping)
        $rawBody = json_encode($data, JSON_UNESCAPED_SLASHES);

        // Combine timestamp + body as per Node.js
        $payload = $timestamp . $rawBody;

        // Generate HMAC-SHA256 signature (hex encoded)
        return hash_hmac('sha256', $payload, $secret);
    }

    /**
     * Verify a received signature (optional)
     */
    public static function verifySignature(string $receivedSignature, array $data, string $secret, $timestamp): bool
    {
        $expected = self::generateSignature($data, $secret, $timestamp);
        return hash_equals($expected, $receivedSignature);
    }
}