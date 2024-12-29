<?php
// views/addtask.php
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
$taskController = new TaskController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $task_name = htmlspecialchars($_POST["task_name"]);
    $task_description = htmlspecialchars($_POST["task_description"]);
    $task_date = htmlspecialchars($_POST["task_date"]);
    $adderid = $_SESSION["ID"];
    $addertype = $_SESSION["is_admin"] ? "Admin" : "User";
    $collab = $_SESSION["is_admin"] ? true : false;

    // Call the controller to add the task
    $taskController->addTask($task_name, $task_description, $task_date, $adderid, $addertype, $collab);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
</head>
<body>
    <h2>Add a New Task</h2>
    <form action="" method="post">
        <label for="task_name">Task Name:</label>
        <input type="text" name="task_name" required>
        <label for="task_description">Task Description:</label>
        <textarea name="task_description" required></textarea>
        <label for="task_date">Task Date:</label>
        <input type="date" name="task_date" required>
        <button type="submit">Add Task</button>
    </form>
</body>
</html>
