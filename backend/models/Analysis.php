<?php
/**
 * BigBully AI - Analysis Model
 */

class Analysis {
    private $db;
    private $table = 'analysis';

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Create analysis
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table}
                (company_id, user_id, analysis_type, financial_health, valuation, growth_metrics, risks, ai_insights)
                VALUES (:company_id, :user_id, :analysis_type, :financial_health, :valuation, :growth_metrics, :risks, :ai_insights)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':company_id' => $data['company_id'],
            ':user_id' => $data['user_id'] ?? null,
            ':analysis_type' => $data['analysis_type'] ?? 'general',
            ':financial_health' => json_encode($data['financial_health'] ?? []),
            ':valuation' => json_encode($data['valuation'] ?? []),
            ':growth_metrics' => json_encode($data['growth_metrics'] ?? []),
            ':risks' => json_encode($data['risks'] ?? []),
            ':ai_insights' => $data['ai_insights'] ?? '',
        ]);
    }

    /**
     * Get latest analysis for company
     */
    public function getByCompany($companyId, $limit = 5) {
        $sql = "SELECT * FROM {$this->table} WHERE company_id = :company_id ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>
