<?php
// app/config/database.php

class Database {
    // Ubah kredensial ini sesuai dengan local server lu (XAMPP/Laragon)
    private $host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
    private $db_name = "asayoripos_db"; // Pastikan lu udah bikin database ini di phpMyAdmin lokal
    private $username = "AwU7dFnNZacRjT1.root";
    private $password = "RkDxxvQCBI6VQPKw";
    private $port = "4000";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                // WAJIB UNTUK TIDB SERVERLESS: Arahkan ke file sertifikat SSL
                PDO::MYSQL_ATTR_SSL_CA       => __DIR__ . '/isrgrootx1.pem',
                // Bypass verifikasi ketat hostname di local server
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ];

            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $exception) {
            die("Koneksi Database Gagal: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>