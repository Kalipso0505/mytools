<?php

namespace App\Helper;

/**
 * Simplified PasswordHelper based on this example.
 *
 * @see: https://www.phpjabbers.com/generate-a-random-password-with-php-php70.html
 */
class PasswordHelper
{
    /**
     * Get password hash of the given password.
     *
     * @param $password
     *
     * @return string
     */
    public static function getPasswordHash($password)
    {
        $passwordHash = '';

        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
        }

        return $passwordHash;
    }

    /**
     * Match the given password with the hashed password.
     *
     * @param string $loginPassword
     * @param string $userHashPassword
     *
     * @return bool
     */
    public static function verifyPassword($loginPassword, $userHashPassword)
    {
        $isVerified = password_verify($loginPassword, $userHashPassword);

        return $isVerified;
    }
}
