<?php

namespace Framework;

use Exception;

class Encryption
{
    private const CIPHER = 'aes-256-gcm';
    private const IV_LENGTH = 12; // Recommended IV size for AES-GCM
    private const TAG_LENGTH = 16;

    public static function encrypt(string $data): string
    {
        $key = self::getKey();

        $iv = random_bytes(self::IV_LENGTH);
        $tag = '';

        $ciphertext = openssl_encrypt(
            $data,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::TAG_LENGTH
        );

        if ($ciphertext === false) {
            throw new Exception('Encryption failed.');
        }

        return base64_encode($iv . $tag . $ciphertext);
    }

    public static function decrypt(string $encoded): string
    {
        $key = self::getKey();

        $decoded = base64_decode($encoded, true);
        if ($decoded === false || strlen($decoded) < self::IV_LENGTH + self::TAG_LENGTH) {
            throw new Exception('Invalid encrypted data.');
        }

        $iv = substr($decoded, 0, self::IV_LENGTH);
        $tag = substr($decoded, self::IV_LENGTH, self::TAG_LENGTH);
        $ciphertext = substr($decoded, self::IV_LENGTH + self::TAG_LENGTH);

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($plaintext === false) {
            throw new Exception('Decryption failed.');
        }

        return $plaintext;
    }

    private static ?string $cachedKey = null;

    private static function getKey(): string
    {
        if (self::$cachedKey === null) {
            $key = base64_decode($_ENV['BASE64_KEY'], true);
            if ($key === false || strlen($key) !== 32) {
                throw new \RuntimeException('Invalid BASE64_KEY in environment.');
            }

            self::$cachedKey = $key;
        }

        return self::$cachedKey;
    }


    public static function generateKey(): string
    {
        return random_bytes(32); // AES-256 = 32 bytes key
    }
}
