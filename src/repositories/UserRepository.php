<?php

require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../Entities/User.php';

/**
 * Accès SQL aux utilisateurs.
 */
class UserRepository
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function findByEmail($email)
    {
        try {
            $sql = 'SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            return $this->hydrate($row);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la recherche par email : ' . $e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $sql = 'SELECT id, name, email, password, role FROM users WHERE id = :id LIMIT 1';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            return $this->hydrate($row);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la recherche utilisateur : ' . $e->getMessage());
        }
    }

    private function hydrate($row)
    {
        return new User(
            (int) $row['id'],
            $row['name'],
            $row['email'],
            $row['password'],
            $row['role']
        );
    }

    public function updatePasswordById($id, $hashedPassword)
    {
        try {
            $sql = 'UPDATE users SET password = :password WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':password' => $hashedPassword,
                ':id' => $id,
            ]);
        } catch (PDOException $e) {
            throw new Exception('Erreur lors de la mise à jour du mot de passe : ' . $e->getMessage());
        }
    }
}
