<?php
namespace App\Models;

use App\Core\Database;

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($firstname, $lastname, $email, $password, $role = 'user') {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (firstname, lastname, email, pwd, role, is_active, created_at)
             VALUES (:firstname, :lastname, :email, :pwd, :role, :is_active, NOW())"
        );

        return $stmt->execute([
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':email' => $email,
            ':pwd' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => $role,
            ':is_active' => true
        ]);
    }

    public function update($id, $firstname, $lastname, $email, $is_active, $role = 'user') {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, is_active = :is_active, role = :role, updated_at = NOW()
             WHERE id = :id"
        );
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $stmt->bindValue(':is_active', (bool)$is_active, \PDO::PARAM_BOOL);
        $stmt->bindValue(':role', $role, \PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function delete($id) {
        // Soft delete : désactiver l'utilisateur et anonymiser l'email pour permettre la réinscription avec le même email (ne permet pas de recupérer les pages de l'utilisateur à la réinscription)
        $deletedEmail = 'deleted_' . time() . '_' . $id . '@deleted.local';
        $stmt = $this->pdo->prepare(
            "UPDATE users SET is_active = false, email = :email, updated_at = NOW()
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id' => $id,
            ':email' => $deletedEmail
        ]);
    }

    public function getByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}