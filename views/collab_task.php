<?php
include_once '../controllers/TaskController.php';
session_start();    
if (!isset($_SESSION['ID'])&& !$_SESSION['is_admin']) {
    // Show an alert message and then redirect to the login page
    echo "<script>
            alert('You must be an Admin');
            window.location.href = 'Login.php'; // Redirect to login page
          </script>";
    exit(); // Stop further execution of the script
}
// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to integer for safety

    // Instantiate the TaskController
    $taskController = new TaskController();

    // Call the method to toggle collaboration status
    $taskController->toggleCollabStatus($id);
}
?>
