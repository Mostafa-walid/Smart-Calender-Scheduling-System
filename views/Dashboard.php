<?php
include_once "../Database/dbh.php";
include '../includes/menu.php';
include_once "../controllers/TaskController.php";
if (!isset($_SESSION['ID'])) {
    // Show an alert message and then redirect to the login page
    echo "<script>
            alert('You must be logged in first');
            window.location.href = 'Login.php'; // Redirect to login page
          </script>";
    exit(); // Stop further execution of the script
}
// Get current date for reference
$today = date('Y-m-d');

$selectedMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$selectedYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

if (isset($_GET['change'])) {
    if ($_GET['change'] === 'prev') {
        $selectedMonth--;
        if ($selectedMonth < 1) {
            $selectedMonth = 12;
            $selectedYear--;
        }
    } elseif ($_GET['change'] === 'next') {
        $selectedMonth++;
        if ($selectedMonth > 12) {
            $selectedMonth = 1;
            $selectedYear++;
        }
    }
}

$userID = $_SESSION['ID'];

$taskController = new TaskController();

// Fetch tasks using Controller
$tasks = $taskController->displayTasks($selectedMonth, $selectedYear, $userID);


function generateCalendar($tasks, $selectedMonth, $selectedYear, $today) {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
    $firstDay = date('N', strtotime("$selectedYear-$selectedMonth-01")); // Fixed to use correct day of the week
    $calendar = "";

    // Create blank cells before the first day
    for ($i = 1; $i <= $firstDay; $i++) {
        $calendar .= "<div class='cell empty'></div>";
    }

    // Generate days
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = "$selectedYear-" . str_pad($selectedMonth, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
        $events = isset($tasks[$date]) ? implode('<br>', $tasks[$date]) : '';
        $class = '';
        if ($date < $today) {
            $class = 'passed';
        } elseif ($date == $today) {
            $class = 'today';
        }
        $calendar .= "<div class='cell $class'>";
        $calendar .= "<div class='date'>$day</div>";
        if ($events) {
            $calendar .= "<div class='event'>$events</div>";
        }
        $calendar .= "</div>";
    }

    return $calendar;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Calendar</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="calendar-wrapper">
    <div class="calendar-header">
        <form method="GET" class="month-selector">
            <h3>
            <button name="change" value="prev">&lt;</button>
            <?php echo date('F Y', strtotime("$selectedYear-$selectedMonth-01")); ?>
            <button name="change" value="next">&gt;</button>
            </h3>
            <input type="hidden" name="month" value="<?php echo $selectedMonth; ?>">
            <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
        </form>
    </div>
    <div class="calendar-grid">
        <?php echo generateCalendar($tasks, $selectedMonth, $selectedYear, $today); ?>
    </div>
</div>

</body>
</html>
