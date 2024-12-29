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
$taskController = new TaskController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taskName = htmlspecialchars($_POST["task_name"]);
    $taskDescription = htmlspecialchars($_POST["task_description"]);
    $taskDate = htmlspecialchars($_POST["task_date"]);
    $adderId = $_SESSION["ID"];
    $adderType = $_SESSION["is_admin"] ? "Admin" : "User";
    $collab = $_SESSION["is_admin"] ? true : false;

    $result = $taskController->addTaskuser($taskName, $taskDescription, $taskDate, $adderId, $adderType, $collab);

    if ($result === true) {
        header("Location: Taskmanag.php");
        exit();
    } else {
        echo $result; // Error message from the model
    }
}
?>
