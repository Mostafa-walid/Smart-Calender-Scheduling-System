<?php
// controllers/TaskController.php
include_once '../models/TaskModel.php';
require_once '../includes/phpmailer/src/PHPMailer.php';
require_once '../includes/phpmailer/src/SMTP.php';
require_once '../includes/phpmailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class TaskController {
    private $taskModel;

    public function __construct() {
        $this->taskModel = new TaskModel(); // Instantiate TaskModel
    }

    // View all tasks
    public function viewTasks() {
        $tasks = $this->taskModel->getTasks1();  // Get tasks from the model
    }  
 

    // Add a task
    public function addTask($task_name, $task_description, $task_date, $adderid, $addertype, $collab) {
        $result = $this->taskModel->addTask($task_name, $task_description, $task_date, $adderid, $addertype, $collab);

        if ($result) {
            $this->sendTaskNotification();
            header("Location: Taskmanag_admin.php");
            exit();
        } else {
            echo "Error adding task.";
        }
    }
    public function addTaskuser($task_name, $task_description, $task_date, $adderid, $addertype, $collab) {
        $result = $this->taskModel->addTask($task_name, $task_description, $task_date, $adderid, $addertype, $collab);

        if ($result) {
            header("Location: Taskmanag.php");
            exit();
        } else {
            echo "Error adding task.";
        }
    }

    private function sendTaskNotification() {
        // Call TaskModel's method to get user emails
        $userEmailsResult = $this->taskModel->getUserEmails();  // This method is already using $conn

        if (mysqli_num_rows($userEmailsResult) > 0) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'gabermostafa863@gmail.com';  // Your Gmail address
                $mail->Password = 'ccdm ldva yvbh qjan';  // Your Gmail App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('gabermostafa863@gmail.com', 'MIUIANS');
                while ($row = mysqli_fetch_assoc($userEmailsResult)) {
                    $mail->addAddress($row['email']); // Add each user's email
                }

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'New Task Notification';
                $mail->Body    = "Hello,<br><br>A new task has been added.<br>Please check your Schedule.<br><br>Regards,<br>MIUIANS";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }


    private function sendTaskEditNotification() {
        // Call TaskModel's method to get user emails
        $userEmailsResult = $this->taskModel->getUserEmails();  // This method is already using $conn

        if (mysqli_num_rows($userEmailsResult) > 0) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'gabermostafa863@gmail.com';  // Your Gmail address
                $mail->Password = 'ccdm ldva yvbh qjan';  // Your Gmail App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('gabermostafa863@gmail.com', 'MIUIANS');
                while ($row = mysqli_fetch_assoc($userEmailsResult)) {
                    $mail->addAddress($row['email']); // Add each user's email
                }

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Task Update Notification';
                $mail->Body    = "Hello,<br><br>A  task has been modified.<br>Please check your Schedule.<br><br>Regards,<br>MIUIANS";

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

    public function sendTaskDeletionNotification() {
        $userEmails = $this->taskModel->getUserEmails();

        if (mysqli_num_rows($userEmails) > 0) {
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
                foreach ($userEmails as $email) {
                    $mail->addAddress($email['email']); // Add each user's email
                }

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Task Deletion Notification';
                $mail->Body    = "Hello,<br><br>A task has been deleted.<br>Please check your Schedule.<br><br>Regards,<br>MIUIANS";

                // Send email
                $mail->send();
                echo 'Email has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

    

    // Delete a task
    public function deleteTask($id) {
        $result = $this->taskModel->deleteTask($id);
        if ($result) {
            $this->sendTaskDeletionNotification();
            header("Location: Taskmanag_admin.php");  // Redirect to the task management page
            exit();
        } else {
            echo "Error deleting task.";
        }
    }
// Delete a task
public function deleteTaskuser($id) {
    return $this->taskModel->deleteTask($id);
    
}

 // Edit task logic
 public function editTask($id, $name, $description, $date) {
    $result = $this->taskModel->updateTask($id, $name, $description, $date);

    if ($result) {
        $this->sendTaskEditNotification(); // Send email notifications
        header("Location: Taskmanag_admin.php");
        exit();
    } else {
        echo "Error updating task.";
    }
}

public function editTaskuser($id, $name, $description, $date) {
    return $this->taskModel->updateTask($id, $name, $description, $date);
}

// Get task by ID
public function getTaskById($id) {
    return $this->taskModel->getTask($id);
}

// Send notification to users
private function sendNotification($taskName) {
    require '../includes/phpmailer/src/PHPMailer.php';
    require '../includes/phpmailer/src/SMTP.php';
    require '../includes/phpmailer/src/Exception.php';

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gabermostafa863@gmail.com';
        $mail->Password = 'ccdm ldva yvbh qjan';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set "From" address
        $mail->setFrom('gabermostafa863@gmail.com', 'MIUIANS');

        // Fetch user emails
        $emails = $this->taskModel->getUserEmails();
        foreach ($emails as $email) {
            $mail->addAddress($email['email']);
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Task Update Notification';
        $mail->Body    = "Hello,<br><br>The task <b>$taskName</b> has been updated.<br>Please check your schedule.<br><br>Regards,<br>MIUIANS";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
      // Fetch all tasks
      public function getAllTasks() {
        return $this->taskModel->getAllTasks();
    }
     public function toggleCollabStatus($id) {
        // Call the model method to toggle the collab value
       

        return $this->taskModel->toggleCollab($id);
    }
    public function displayTasks($selectedMonth, $selectedYear, $userID) {
        $tasks = $this->taskModel->getTasks($selectedMonth, $selectedYear, $userID);

        return $tasks;
    }

    
}
?>
