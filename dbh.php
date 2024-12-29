<?php

class Database {
    private static $instance = null; // Singleton instance
    private $connection; // Database connection

    // Database configuration
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbName = "smart_calender";

    // Private constructor to prevent multiple instances
    private function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->dbName);

        // Check if the connection was successful
        if ($this->connection->connect_error) {
            // Use exception for better error handling
            throw new Exception("Connection failed: " . $this->connection->connect_error);
        }
    }

    // Get the singleton instance of the Database class
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Get the database connection
    public function getConnection() {
        return $this->connection;
    }

    // Close the database connection and reset the instance
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
        self::$instance = null;
    }

    // Prevent cloning of the instance (Singleton pattern)
    private function __clone() {}

    // Make __wakeup public to avoid warning
    public function __wakeup() {}
}
?>
