<?php
class SettingModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllSettings() {
        try {
            $stmt = $this->db->query("SELECT setting_key, setting_value FROM settings");
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[$row['setting_key']] = $row['setting_value'];
            }
            return $data;
        } catch (\PDOException $e) {
            return [];
        }
    }

    public function saveSettings($data) {
        try {
            // REPLACE INTO akan otomatis insert baru atau timpa (update) kalau key sudah ada
            $stmt = $this->db->prepare("REPLACE INTO settings (setting_key, setting_value) VALUES (:key, :val)");
            foreach ($data as $key => $val) {
                $stmt->execute([
                    ':key' => $key,
                    ':val' => $val
                ]);
            }
            return ['status' => 'success'];
        } catch (\PDOException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}