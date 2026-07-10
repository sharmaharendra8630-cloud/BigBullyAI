<?php
/**
 * BigBully AI - Research Model
 */

class Research {
    private $db;
    private $table = 'research';

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Get research for company
     */
    public function getByCompany($companyId) {
        $sql = "SELECT * FROM {$this->table} WHERE company_id = :company_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':company_id' => $companyId]);
        return $stmt->fetchAll();
    }

    /**
     * Create research
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (company_id, title, summary, content, confidence_score, sources, limitations)
                VALUES (:company_id, :title, :summary, :content, :confidence_score, :sources, :limitations)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':company_id' => $data['company_id'],
            ':title' => $data['title'],
            ':summary' => $data['summary'],
            ':content' => $data['content'],
            ':confidence_score' => $data['confidence_score'] ?? 0.85,
            ':sources' => json_encode($data['sources'] ?? []),
            ':limitations' => $data['limitations'],
        ]);
    }

    /**
     * Get latest research
     */
    public function getLatest($limit = 20) {
        $sql = "SELECT r.*, c.symbol, c.name FROM {$this->table} r
                JOIN companies c ON r.company_id = c.id
                ORDER BY r.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>
