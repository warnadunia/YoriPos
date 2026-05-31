<?php
// =========================================================
// 1. CENTRALIZED CONFIGURATION (Pengaturan Kunci API & Server)
// =========================================================
define('BLOB_READ_WRITE_TOKEN', getenv('BLOB_READ_WRITE_TOKEN') ?: 'vercel_blob_rw_nqfPnuiuW2lY0KRe_Ss57T1f5TlP6FtJ0qDyhotllFkfCqK');
define('APP_URL', getenv('VERCEL_URL') ? 'https://' . getenv('VERCEL_URL') : 'http://localhost/yoripos');


// =========================================================
// 2. PANGGIL KONEKSI DATABASE LU
// =========================================================
require_once __DIR__ . '/database.php';
$database = new Database();
$db = $database->getConnection();


// =========================================================
// 3. VAKSIN ANTI-AMNESIA VERCEL (DATABASE SESSION HANDLER)
// =========================================================
class DBSessionHandler implements SessionHandlerInterface {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }
    
    #[\ReturnTypeWillChange]
    public function open($path, $name) { return true; }
    
    #[\ReturnTypeWillChange]
    public function close() { return true; }
    
    #[\ReturnTypeWillChange]
    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { return $row['data']; }
        return '';
    }
    
    #[\ReturnTypeWillChange]
    public function write($id, $data) {
        $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, data, timestamp) VALUES (?, ?, ?)");
        return $stmt->execute([$id, $data, time()]);
    }
    
    #[\ReturnTypeWillChange]
    public function destroy($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    #[\ReturnTypeWillChange]
    public function gc($max_lifetime) {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE timestamp < ?");
        return $stmt->execute([time() - $max_lifetime]);
    }
}

// Aktifkan handler database sesaat sebelum session_start()
session_set_save_handler(new DBSessionHandler($db), true);

// =========================================================
// 4. MULAI SESSION
// =========================================================
session_start();
?>