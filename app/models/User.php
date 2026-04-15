<?php
namespace App\Models;

use App\Core\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function login($username, $password)
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE username = :username", ['username' => $username]);
        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        if (empty($user['password_hash']) || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        return $user;
    }

    public function updatePassword($userId, $newPassword)
    {
        $sql = "UPDATE users
                SET password_hash = :password_hash,
                    must_change_password = 0
                WHERE user_id = :user_id";

        return $this->db->query($sql, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
            'user_id' => $userId
        ]);
    }

    public function getById($userId)
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE user_id = :user_id", ['user_id' => $userId]);
        return $stmt->fetch();
    }

    public function verifyPassword($user, $plainPassword)
    {
        return !empty($user['password_hash'])
            && password_verify($plainPassword, $user['password_hash']);
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE username = :username", ['username' => $username]);
        return $stmt->fetch();
    }

    public function getFirstUser()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY user_id ASC LIMIT 1");
        return $stmt->fetch();
    }

    public function hasSecurityQuestion($user)
    {
        return !empty($user['security_question']) && !empty($user['security_answer_hash']);
    }

    public function setSecurityQuestion($userId, $question, $answer)
    {
        $question = trim((string) $question);
        $answer = trim((string) $answer);

        if ($question === '' || $answer === '') {
            return false;
        }

        return $this->db->query(
            "UPDATE users
             SET security_question = :security_question,
                 security_answer_hash = :security_answer_hash
             WHERE user_id = :user_id",
            [
                'security_question' => $question,
                'security_answer_hash' => password_hash(strtolower($answer), PASSWORD_DEFAULT),
                'user_id' => $userId
            ]
        );
    }

    public function verifySecurityAnswer($user, $plainAnswer)
    {
        $hash = $user['security_answer_hash'] ?? '';
        if ($hash === '') {
            return false;
        }

        $normalized = strtolower(trim((string) $plainAnswer));
        return password_verify($normalized, $hash);
    }
}
