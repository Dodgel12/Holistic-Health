<?php
/**
 * Gestisce i token per il recupero password.
 */
namespace App\Models;

use App\Core\Database;

class PasswordReset
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createToken($userId)
    {
        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = password_hash($rawToken, PASSWORD_DEFAULT);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $this->db->query(
            "INSERT INTO password_reset_tokens (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, :expires_at)",
            [
                'user_id' => $userId,
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt
            ]
        );

        return $rawToken;
    }

    public function resolveValidToken($token)
    {
        $rows = $this->db->query(
            "SELECT * FROM password_reset_tokens
             WHERE used_at IS NULL
               AND expires_at >= NOW()
             ORDER BY token_id DESC"
        )->fetchAll();

        foreach ($rows as $row) {
            if (password_verify($token, $row['token_hash'])) {
                return $row;
            }
        }

        return null;
    }

    public function markUsed($tokenId)
    {
        return $this->db->query(
            "UPDATE password_reset_tokens SET used_at = NOW() WHERE token_id = :id",
            ['id' => $tokenId]
        );
    }
}
