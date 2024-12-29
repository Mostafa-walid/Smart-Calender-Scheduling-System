<?php
require '../controllers/UserController.php';
session_start();    
if (!isset($_SESSION['ID'])&& !$_SESSION['is_admin']) {
    // Show an alert message and then redirect to the login page
    echo "<script>
            alert('You must be an Admin');
            window.location.href = 'Login.php'; // Redirect to login page
          </script>";
    exit(); // Stop further execution of the script
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $userController = new UserController();
    $message = $userController->registerUser($username, $password, $email);

    echo "<script>alert('$message');</script>";
    if (strpos($message, "Success") !== false) {
        header("Location: UserManagment.php"); // Redirect on success
        exit();
    }
}
?>
