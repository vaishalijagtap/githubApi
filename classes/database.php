<?php
// Database connectivity class 
class Database{
  
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "github_api";
    private $username = "root";
    private $password = "maithili@2";
    public $conn;
  
    // get the database connection
    function __construct(){
  
        $this->conn = null;
  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
    }
}
?>