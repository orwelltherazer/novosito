<?php
/**
 * User Model
 */

namespace App\Models;

use Core\Model;

class User extends Model {
    protected $table = 'users';

    /**
     * Authentifie un utilisateur
     */
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Met à jour la dernière connexion
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }

        return false;
    }

    /**
     * Trouve un utilisateur par email
     */
    public function findByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        return $this->db->selectOne($query, ['email' => $email]);
    }

    /**
     * Trouve un utilisateur par username
     */
    public function findByUsername($username) {
        $query = "SELECT * FROM {$this->table} WHERE username = :username";
        return $this->db->selectOne($query, ['username' => $username]);
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser($data) {
        // Hash le mot de passe
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }

    /**
     * Met à jour le mot de passe
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
}
