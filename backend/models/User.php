<?php
/**
 * BigBully AI - User Model
 */

class User {
    private $db;
    private $table = 'users';

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Create new user
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (email, password_hash, first_name, last_name) 
                VALUES (:email, :password_hash, :first_name, :last_name)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':first_name' => $data['first_name'] ?? null,
            ':last_name' => $data['last_name'] ?? null,
        ]);
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Update user profile
     */
    public function update($id, $data) {
        $allowedFields = ['first_name', 'last_name', 'phone', 'profile_pic'];
        $updates = [];
        $params = [':id' => $id];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "{$field} = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Get user subscription
     */
    public function getSubscription($userId) {
        $sql = "SELECT subscription_type FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $userId]);
        $result = $stmt->fetch();
        return $result['subscription_type'] ?? 'free';
    }
}

?>
