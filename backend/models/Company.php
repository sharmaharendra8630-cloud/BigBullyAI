<?php
/**
 * BigBully AI - Company Model
 */

class Company {
    private $db;
    private $table = 'companies';

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Get all companies with filters
     */
    public function getAll($filters = []) {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['sector'])) {
            $sql .= " AND sector = :sector";
            $params[':sector'] = $filters['sector'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR symbol LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY name ASC LIMIT 50";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get company by symbol
     */
    public function getBySymbol($symbol) {
        $sql = "SELECT * FROM {$this->table} WHERE symbol = :symbol";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':symbol' => $symbol]);
        return $stmt->fetch();
    }

    /**
     * Get company by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Get company details with latest price
     */
    public function getWithPrice($symbol) {
        $sql = "SELECT c.*, sp.price, sp.change, sp.change_percent, sp.volume, sp.price_date
                FROM {$this->table} c
                LEFT JOIN stock_prices sp ON c.id = sp.company_id
                WHERE c.symbol = :symbol
                ORDER BY sp.price_date DESC
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':symbol' => $symbol]);
        return $stmt->fetch();
    }

    /**
     * Create company
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (symbol, name, sector, industry, website, description, market_cap, pe_ratio, dividend_yield, employees, founded_year, nse_listed, bse_listed)
                VALUES (:symbol, :name, :sector, :industry, :website, :description, :market_cap, :pe_ratio, :dividend_yield, :employees, :founded_year, :nse_listed, :bse_listed)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Get trending companies
     */
    public function getTrending($limit = 10) {
        $sql = "SELECT c.* FROM {$this->table} c
                WHERE c.nse_listed = 1 OR c.bse_listed = 1
                ORDER BY c.market_cap DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>
