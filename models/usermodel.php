<?php
include_once '../database/dbh.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function login($usernameOrEmail, $password) {
        $query = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;  // Valid user
        }
        return null;  // Invalid credentials
    }

    public function signup($username, $email, $password) {
        if ($this->userExists($username, $email)) {
            return false;  // User already exists
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sss', $username, $email, $hashedPassword);
        
        return $stmt->execute();  // Returns true if signup succeeds
    }

    public function addTask($taskName, $taskDescription, $taskDate, $adderId, $adderType, $collab) {
        $query = "INSERT INTO tasks (task_name, task_description, task_date, adderid, addertype, collab) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssssss', $taskName, $taskDescription, $taskDate, $adderId, $adderType, $collab);
        
        return $stmt->execute();  // Returns true if task is added
    }

    private function userExists($username, $email) {
        $query = "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();
        
        return $stmt->num_rows > 0;  // True if user exists
    }

    public function createUser($username, $password, $email) {
        if ($this->userExists($username, $email)) {
            return false;  // User already exists
        }
        $sql = "INSERT INTO users (Username, Password, Email) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $email);
        return $stmt->execute();
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
    }

    public function deleteUserById($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function updateUser($id, $name, $email, $password = null) {
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET Username = ?, Email = ?, Password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $hashed_password, $id);
        } else {
            $sql = "UPDATE users SET Username = ?, Email = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $name, $email, $id);
        }
        return $stmt->execute();
    }
}
?>
