<?php
include_once '../controllers/TaskController.php';
session_start();
if (!isset($_SESSION['ID'])) {
  // Show an alert message and then redirect to the login page
  echo "<script>
          alert('You must be logged in');
          window.location.href = 'Login.php'; // Redirect to login page
        </script>";
  exit(); // Stop further execution of the script
}
if (isset($_GET['id'])) {
    $taskId = (int)$_GET['id'];
    $taskController = new TaskController();

    $statusMessage = ($taskController->toggleCollabStatus($taskId)? "enabled" : "disabled");

    if ($statusMessage === "enabled" || $statusMessage === "disabled") {
        echo "<script>
                alert('Collab status is now $statusMessage.');
                window.location.href = 'Taskmanag.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: $statusMessage');
                window.location.href = 'Taskmanag.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Task ID not provided.');
            window.location.href = 'Taskmanag.php';
          </script>";
}
?>
