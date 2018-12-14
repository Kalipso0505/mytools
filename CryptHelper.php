<?php

namespace App\Helper;

class CryptHelper
{
    /** @var string */
    private $key;

    /**
     * @var string method used to encrypt the data
     */
    private $cipher;

    /**
     * cryptHelper constructor.
     *
     * @param string $key
     * @param string $cipher
     */
    public function __construct(string $key, string $cipher)
    {
        $this->key = $key;
        $this->cipher = $cipher;
    }

    /**
     * @param string $dataToEncrypt
     *
     * @return string
     */
    public function crypt(string $dataToEncrypt): string
    {
        $ivLen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLen);

        return base64_encode(
            $iv .
            openssl_encrypt(
                $dataToEncrypt,
                $this->cipher,
                $this->key,
                $options = 0,
                $iv
            )
        );
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function decrypt(string $token): string
    {
        $ivLen = openssl_cipher_iv_length($this->cipher);
        $decoded = base64_decode($token);
        $iv = mb_substr($decoded, 0, $ivLen, '8bit');
        $cipherText = mb_substr($decoded, $ivLen, null, '8bit');

        return openssl_decrypt(
            $cipherText,
            $this->cipher,
            $this->key,
            $options = 0,
            $iv
        );
    }
}
