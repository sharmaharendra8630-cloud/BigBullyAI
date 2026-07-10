<?php
/**
 * BigBully AI - Input Validation Helper
 */

class Validator {
    private $errors = [];

    /**
     * Validate email
     */
    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format';
            return false;
        }
        return true;
    }

    /**
     * Validate password
     */
    public function validatePassword($password) {
        if (strlen($password) < 8) {
            $this->errors['password'] = 'Password must be at least 8 characters';
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors['password'] = 'Password must contain uppercase letter';
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->errors['password'] = 'Password must contain number';
            return false;
        }
        return true;
    }

    /**
     * Validate required fields
     */
    public function required($field, $value) {
        if (empty($value)) {
            $this->errors[$field] = ucfirst($field) . ' is required';
            return false;
        }
        return true;
    }

    /**
     * Validate string length
     */
    public function stringLength($field, $value, $min = 0, $max = null) {
        $length = strlen($value);
        if ($length < $min) {
            $this->errors[$field] = ucfirst($field) . " must be at least $min characters";
            return false;
        }
        if ($max && $length > $max) {
            $this->errors[$field] = ucfirst($field) . " must not exceed $max characters";
            return false;
        }
        return true;
    }

    /**
     * Get all errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Has errors
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
}

?>
