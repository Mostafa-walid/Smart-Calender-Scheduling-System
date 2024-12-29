<?php
include_once '../database/dbh.php';

class TaskModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();

    }

    public function addTask($task_name, $task_description, $task_date, $adderid, $addertype, $collab) {
        $sql = "INSERT INTO tasks (task_name, task_description, task_date, adderid, addertype, collab) 
                VALUES ('$task_name', '$task_description', '$task_date', '$adderid', '$addertype', '$collab')";
        return $this->conn->query($sql);
    }

    public function getTasks1() {
        $sql = "SELECT * FROM tasks";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function deleteTask($id) {
        $sql = "DELETE FROM tasks WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

        public function getTask($id) {
            $sql = "SELECT * FROM tasks WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
    
        public function updateTask($id, $name, $description, $date) {
            $sql = "UPDATE tasks SET task_name = ?, task_description = ?, task_date = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sssi', $name, $description, $date, $id);
            return $stmt->execute();
        }
    
        public function getUserEmails() {
            $sql = "SELECT email FROM users";
            return mysqli_query($this->conn, $sql); 
        }

        public function getAllTasks() {
            $sql = "SELECT tasks.*, users.username 
                    FROM tasks 
                    LEFT JOIN users 
                    ON tasks.adderid = users.id";
                    
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function toggleCollab($id) {
            $toggleSql = "UPDATE tasks SET collab = NOT collab WHERE id = ?";
            $stmt = mysqli_prepare($this->conn, $toggleSql);
            mysqli_stmt_bind_param($stmt, 'i', $id);
    
            if (mysqli_stmt_execute($stmt)) {
                $statusSql = "SELECT collab FROM tasks WHERE id = ?";
                $stmt = mysqli_prepare($this->conn, $statusSql);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
    
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)) {
                    return (bool) $row['collab']; // Return the updated status
                }
            }
    
            return false; // Return false if the operation fails
        }

        public function getTasks($selectedMonth, $selectedYear, $userID) {
            $sql = "SELECT task_name, task_date 
                    FROM tasks 
                    WHERE MONTH(task_date) = ? 
                      AND YEAR(task_date) = ? 
                      AND (addertype = 'Admin' 
                           OR adderid = ? 
                           OR (addertype != 'Admin' AND collab = 1))";
            $stmt = Database::getInstance()->getconnection()->prepare($sql);
            $stmt->bind_param("iii", $selectedMonth, $selectedYear, $userID);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $tasks = [];
            while ($row = $result->fetch_assoc()) {
                $tasks[$row['task_date']][] = $row['task_name'];
            }
    
            return $tasks;
        }
}
?>
