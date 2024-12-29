<?php
include_once '../models/AdminModel.php';

class AdminController {
    private $adminModel;

    public function __construct() {
        $this->adminModel = new AdminModel();  // Instantiate AdminModel
    }

    public function viewUsers() {
        $users = $this->adminModel->getAllUsers();  // Get all users from the database
        include_once '../views/UserManagement.php';  // Pass data to the view
    }

    public function addUser($username, $email, $password) {
        $result = $this->adminModel->addUser($username, $email, $password);
        if ($result) {
            header("Location: admin_dashboard.php");  // Redirect to the dashboard
            exit();
        } else {
            echo "Error adding user.";
        }
    }

    public function editUser($id, $username, $email, $password) {
        $result = $this->adminModel->editUser($id, $username, $email, $password);
        if ($result) {
            header("Location: admin_dashboard.php");  // Redirect to the dashboard
            exit();
        } else {
            echo "Error editing user.";
        }
    }

    public function deleteUser($id) {
        $result = $this->adminModel->deleteUser($id);
        if ($result) {
            header("Location: admin_dashboard.php");  // Redirect to the dashboard
            exit();
        } else {
            echo "Error deleting user.";
        }
    }
}
?>
