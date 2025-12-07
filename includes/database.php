<?php
// Check if config.php exists
$config_path = __DIR__ . '/config.php';
if (!file_exists($config_path)) {
    die('File config.php tidak ditemukan di: ' . $config_path);
}

require_once $config_path;

class Database {
    private $connection;
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->connect();
    }
    
    private function connect() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            // Set charset to UTF-8
            $this->connection->set_charset("utf8");
            
        } catch (Exception $e) {
            // Don't show detailed error to users
            error_log("Database connection error: " . $e->getMessage());
            die("Koneksi database gagal. Silakan coba lagi nanti.");
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
    
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    public function getLastInsertId() {
        return $this->connection->insert_id;
    }
    
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    // Prevent cloning
    private function __clone() { }
    
    // Prevent unserialization - harus public visibility
    public function __wakeup() { 
        throw new Exception("Cannot unserialize singleton");
    }
}

// Create global database instance
$database = Database::getInstance();
$conn = $database->getConnection();
?>