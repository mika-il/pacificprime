<?php
require(dirname(__FILE__)."/db.php");

Class Connection {
 
  private $Host = DBHost;
	private $DBName = DBName;
	private $DBUser = DBUser;
	private $DBPassword = DBPassword;
  private $options  = array(
                       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                      );
  protected $conn;
   
  public function openConnection() {
    try {
      $this->conn = new PDO('mysql:host=' . $this->Host .';dbname=' . $this->DBName . ';port=' . $this->DBPort, 
                            $this->DBUser, 
                            $this->DBPassword,
                            $this->options
                          );
      return $this->conn;
    } catch (PDOException $e) {
      echo "There is some problem in connection: " . $e->getMessage();
    }
  }
   
  public function closeConnection() {
       $this->conn = null;
  }
 
}


?>