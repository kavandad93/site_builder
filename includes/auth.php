<?php
/**
 * =============================================
 * سیستم احراز هویت با JSON
 * =============================================
 */

class Auth {
    private static $instance = null;
    private $db;
    private $current_user = null;
    
    private function __construct() {
        $this->db = DataManager::getInstance();
        $this->init_session();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function init_session() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            $this->current_user = $this->get_user_by_id($_SESSION['user_id']);
        }
    }
    
    public function login($username, $password) {
        $users = $this->db->get('users');
        $user = null;
        
        foreach ($users as $u) {
            if ($u['username'] == $username || $u['email'] == $username) {
                $user = $u;
                break;
            }
        }
        
        if (!$user || $user['user_status'] != 'active') {
            return ['success' => false, 'message' => 'نام کاربری یا رمز عبور اشتباه است!'];
        }
        
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'نام کاربری یا رمز عبور اشتباه است!'];
        }
        
        $this->db->update('users', $user['id'], ['last_login' => date('Y-m-d H:i:s')]);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $this->current_user = $user;
        
        return ['success' => true, 'user' => $user];
    }
    
    public function logout() {
        session_destroy();
        $this->current_user = null;
        return true;
    }
    
    public function is_logged_in() {
        return $this->current_user !== null;
    }
    
    public function get_current_user() {
        return $this->current_user;
    }
    
    public function get_user_by_id($id) {
        return $this->db->get_by_id('users', $id);
    }
    
    public function get_user_by_username($username) {
        return $this->db->get_by_field('users', 'username', $username);
    }
    
    public function get_users($filters = []) {
        return $this->db->get('users');
    }
    
    public function create_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['registered_at'] = date('Y-m-d H:i:s');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('users', $data);
    }
    
    public function update_user($id, $data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('users', $id, $data);
    }
    
    public function delete_user($id) {
        $users = $this->get_users();
        $admins = array_filter($users, function($u) {
            return $u['role'] == 'administrator';
        });
        
        if (count($admins) === 1 && $admins[0]['id'] == $id) {
            return ['success' => false, 'message' => 'نمی‌توان آخرین ادمین را حذف کرد!'];
        }
        
        return ['success' => true, 'deleted' => $this->db->delete('users', $id)];
    }
    
    public function has_role($role) {
        if (!$this->is_logged_in()) return false;
        if ($this->current_user['role'] === 'administrator') return true;
        return $this->current_user['role'] === $role;
    }
    
    public function is_admin() {
        return $this->has_role('administrator');
    }
}

function auth() {
    return Auth::getInstance();
}

function is_logged_in() {
    return auth()->is_logged_in();
}

function current_user() {
    return auth()->get_current_user();
}

function is_admin() {
    return auth()->is_admin();
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        die('❌ دسترسی غیرمجاز!');
    }
}
?>