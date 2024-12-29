<?php
// controllers/UserController.php
include_once '../models/usermodel.php';
require_once '../includes/phpmailer/src/PHPMailer.php';
require_once '../includes/phpmailer/src/SMTP.php';
require_once '../includes/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class UserController {

    private $userModel;
    public function __construct() {
        $this->userModel=new User();
    }

    public function login($usernameOrEmail, $password) {
        session_start();

        $loggedInUser = $this->userModel->login($usernameOrEmail, $password);

        if ($loggedInUser) {
            $_SESSION['ID'] = $loggedInUser['id'];
            $_SESSION['is_admin'] = $loggedInUser['is_admin'];  

            if ($_SESSION['is_admin']) {
                header("Location: ../views/index.php");
            } else {
                header("Location: ../views/index.php");
            }
            exit();
        } else {
            return "Invalid username/email or password!";
        }
    }

    public function signup($username, $email, $password) {

        // Call signup method to create a user
        $signupResult = $this->userModel->signup($username, $email, $password);

        if ($signupResult) {
            return "User created successfully!";
        } else {
            return "User already exists!";
        }
    }

    public function addTask($taskName, $taskDescription, $taskDate) {
        session_start();

        // Check if user is logged in
        if (!isset($_SESSION['ID'])) {
            return "You need to be logged in to add a task!";
        }

        // Get user details from session
        $adderId = $_SESSION["ID"];
        $adderType = $_SESSION["is_admin"] ? "Admin" : "User";
        $collab = $_SESSION["is_admin"] ? true : false;

        // Create an instance of User model
 

        // Add task through the model
        $taskAdded = $this->userModel->addTask($taskName, $taskDescription, $taskDate, $adderId, $adderType, $collab);

        if ($taskAdded) {
            header("Location: ../views/Taskmanag.php");
            exit();
        } else {
            return "Error adding task!";
        }
    }


    public function registerUser($username, $password, $email) {


        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        if ($this->userModel->createUser($username, $hashedPassword, $email)) {
            // Send confirmation email
            return $this->sendConfirmationEmail($username, $email);
        }

        return "Error: Unable to register user.";
    }

    private function sendConfirmationEmail($username, $email) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gabermostafa863@gmail.com'; // Your Gmail address
            $mail->Password = 'ccdm ldva yvbh qjan'; // Your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('gabermostafa863@gmail.com', 'MIUIANS');
            $mail->addAddress($email, $username);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Account Created';
            $mail->Body = "Hello $username,<br><br>Your account has been created successfully.<br>
                           Welcome to the Smart Calendar System.<br><br>Regards,<br>MIUIANS";

            $mail->send();
            return "Success: User registered and email sent.";
        } catch (Exception $e) {
            return "Error: Unable to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    }


    public function deleteUser($id) {
        if ($this->userModel->deleteUserById($id)) {
            header("Location: UserManagment.php");
            exit();
        } else {
            echo "Error deleting user.";
        }
    }

    public function listUsers() {
        return $this->userModel->getAllUsers();
    }


    public function editUser($id) {
        $user = $this->userModel->getUserById($id);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars($_POST["name"]);
            $email = htmlspecialchars($_POST["email"]);
            $password = !empty($_POST["password"]) ? htmlspecialchars($_POST["password"]) : null;

            if ($this->userModel->updateUser($id, $name, $email, $password)) {
                // Send email directly in the controller
                $this->sendUserUpdateEmail($email, $name);

                header("Location: UserManagment.php");
                exit();
            } else {
                echo "Error updating user.";
            }
        }
        return $user;
    }

    // Email functionality moved here from the helper
    private function sendUserUpdateEmail($email, $name) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gabermostafa863@gmail.com'; // Your Gmail address
            $mail->Password = 'ccdm ldva yvbh qjan'; // Your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('gabermostafa863@gmail.com', 'Your Team'); // Your "From" address
            $mail->addAddress($email, $email); // Recipient email and name

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Account Details Updated';
            $mail->Body    = "Hello $name,<br><br>Your account details have been successfully updated.<br>If you did not request this change, please contact support immediately.<br><br>Regards,<br>Your Team";

            $mail->send();
            echo 'Email has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function getUserData($userID) {
        return $this->userModel->getUserById($userID);
    }
}
?>
