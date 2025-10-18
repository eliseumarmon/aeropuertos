<?php 
class Database {
    private $host;
    private $db;
    private $user; 
    private $pass;
    private $charset = 'utf8mb4';

    public $pdo = null;

    public function __construct() {
        $this->host =   getenv('MYSQL_HOST');
        $this->db =     getenv('MYSQL_DATABASE');
        $this->user =   getenv('MYSQL_USER');
        $this->pass =   getenv('MYSQL_PASSWORD');
    }

    public function connect() {
        if ($this->pdo === null) {
            $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
            try {
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "<p>Connected to the database successfully!</p>";
            } catch (PDOException $e) {
                echo "<p>Connection failed: " . $e->getMessage() . "</p>";
            }
        }
        return $this->pdo;
    }
}