<?php
/**
 * BigBully AI - Database Schema & Migrations
 * Run this to create all tables
 */

require_once __DIR__ . '/../config/Database.php';

class Migration {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function migrate() {
        echo "🚀 Starting migrations...\n";
        
        $this->createUsersTable();
        $this->createCompaniesTable();
        $this->createStockPricesTable();
        $this->createWatchlistTable();
        $this->createResearchTable();
        $this->createAnalysisTable();
        $this->createSubscriptionsTable();
        $this->createPaymentsTable();
        $this->createAuditLogsTable();

        echo "✅ All migrations completed!\n";
    }

    private function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            email VARCHAR(255) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            phone VARCHAR(20),
            subscription_type ENUM('free', 'pro', 'premium') DEFAULT 'free',
            profile_pic VARCHAR(255),
            is_active BOOLEAN DEFAULT TRUE,
            is_verified BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Users table created\n";
    }

    private function createCompaniesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS companies (
            id INT PRIMARY KEY AUTO_INCREMENT,
            symbol VARCHAR(10) NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            sector VARCHAR(100),
            industry VARCHAR(100),
            website VARCHAR(255),
            description TEXT,
            market_cap BIGINT,
            pe_ratio DECIMAL(10, 2),
            dividend_yield DECIMAL(10, 2),
            employees INT,
            founded_year INT,
            nse_listed BOOLEAN DEFAULT FALSE,
            bse_listed BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_symbol (symbol),
            INDEX idx_sector (sector)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Companies table created\n";
    }

    private function createStockPricesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS stock_prices (
            id INT PRIMARY KEY AUTO_INCREMENT,
            company_id INT NOT NULL,
            price DECIMAL(10, 2),
            open DECIMAL(10, 2),
            high DECIMAL(10, 2),
            low DECIMAL(10, 2),
            volume BIGINT,
            change DECIMAL(10, 2),
            change_percent DECIMAL(10, 2),
            market_cap BIGINT,
            price_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
            INDEX idx_company_id (company_id),
            INDEX idx_price_date (price_date),
            UNIQUE KEY unique_company_date (company_id, price_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Stock Prices table created\n";
    }

    private function createWatchlistTable() {
        $sql = "CREATE TABLE IF NOT EXISTS watchlist (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            company_id INT NOT NULL,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
            UNIQUE KEY unique_user_company (user_id, company_id),
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Watchlist table created\n";
    }

    private function createResearchTable() {
        $sql = "CREATE TABLE IF NOT EXISTS research (
            id INT PRIMARY KEY AUTO_INCREMENT,
            company_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            summary TEXT,
            content LONGTEXT,
            confidence_score DECIMAL(3, 2),
            sources JSON,
            limitations TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
            INDEX idx_company_id (company_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Research table created\n";
    }

    private function createAnalysisTable() {
        $sql = "CREATE TABLE IF NOT EXISTS analysis (
            id INT PRIMARY KEY AUTO_INCREMENT,
            company_id INT NOT NULL,
            user_id INT,
            analysis_type VARCHAR(50),
            financial_health JSON,
            valuation JSON,
            growth_metrics JSON,
            risks JSON,
            ai_insights TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
            INDEX idx_company_id (company_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Analysis table created\n";
    }

    private function createSubscriptionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS subscriptions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            plan_type VARCHAR(50),
            price DECIMAL(10, 2),
            billing_cycle VARCHAR(20),
            start_date DATE,
            end_date DATE,
            status VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_id (user_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Subscriptions table created\n";
    }

    private function createPaymentsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS payments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            subscription_id INT,
            amount DECIMAL(10, 2),
            currency VARCHAR(3),
            payment_method VARCHAR(50),
            razorpay_payment_id VARCHAR(255),
            razorpay_order_id VARCHAR(255),
            status VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE SET NULL,
            INDEX idx_user_id (user_id),
            INDEX idx_status (status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Payments table created\n";
    }

    private function createAuditLogsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT,
            action VARCHAR(255),
            resource_type VARCHAR(100),
            resource_id INT,
            details JSON,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $this->db->exec($sql);
        echo "✓ Audit Logs table created\n";
    }
}

// Run migrations
$migration = new Migration();
$migration->migrate();

?>
