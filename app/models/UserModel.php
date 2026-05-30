<?php
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        try {
            // AUTO-SETUP: Kalau tabel users kosong, otomatis bikin akun Super Admin!
            $check = $this->db->query("SELECT COUNT(*) FROM users");
            if ($check->fetchColumn() == 0) {
                $this->db->query("INSERT INTO roles (name, permissions) VALUES ('Owner', '[\"pos\", \"dashboard\", \"products\", \"stocks\", \"transactions\", \"settings\", \"users\"]')");
                $role_id = $this->db->lastInsertId();
                $hash = password_hash('admin123', PASSWORD_DEFAULT);
                $this->db->query("INSERT INTO users (username, password, name, role_id) VALUES ('admin', '$hash', 'Super Admin', $role_id)");
            }

            // Validasi Login
            $stmt = $this->db->prepare("
                SELECT u.id, u.username, u.password, u.name, r.name as role_name, r.permissions 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.username = :username
            ");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Set sesi login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role_name'];
                $_SESSION['permissions'] = json_decode($user['permissions'], true) ?? [];
                
                return ['status' => 'success', 'message' => 'Login berhasil!'];
            }

            return ['status' => 'error', 'message' => 'Username atau password salah!'];
        } catch (\PDOException $e) {
            return ['status' => 'error', 'message' => 'Error DB: ' . $e->getMessage()];
        }
    }
}