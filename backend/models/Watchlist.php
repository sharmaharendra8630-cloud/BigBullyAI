<?php
/**
 * BigBully AI - Watchlist Model
 */

class Watchlist {
    private $db;
    private $table = 'watchlist';

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Get user watchlist
     */
    public function getByUser($userId) {
        $sql = "SELECT c.* FROM companies c
                JOIN {$this->table} w ON c.id = w.company_id
                WHERE w.user_id = :user_id
                ORDER BY w.added_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Add to watchlist
     */
    public function add($userId, $companyId) {
        $sql = "INSERT INTO {$this->table} (user_id, company_id) VALUES (:user_id, :company_id)";
        $stmt = $this->db->prepare($sql);
        
        try {
            return $stmt->execute([
                ':user_id' => $userId,
                ':company_id' => $companyId,
            ]);
        } catch (PDOException $e) {
            // Already exists
            return false;
        }
    }

    /**
     * Remove from watchlist
     */
    public function remove($userId, $companyId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id AND company_id = :company_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':company_id' => $companyId,
        ]);
    }

    /**
     * Check if in watchlist
     */
    public function exists($userId, $companyId) {
        $sql = "SELECT id FROM {$this->table} WHERE user_id = :user_id AND company_id = :company_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':company_id' => $companyId,
        ]);
        return $stmt->fetch() !== false;
    }
}

?>
