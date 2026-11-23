<?php
require_once __DIR__ . '/../core/BaseModel.php';

class User extends BaseModel {
    private ?int $id = null;
    private string $username = '';

    public function __construct(PDO $db) {
        parent::__construct($db);
    }

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }

    public function getAll(): array {
        $stmt = $this->db->query("SELECT id, username, created_at FROM users");
        return $stmt->fetchAll();
    }

    public function login(string $username, string $password): bool {
        $stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $this->id = (int)$user['id'];
            $this->username = $user['username'];
            return true;
        }
        return false;
    }
}
