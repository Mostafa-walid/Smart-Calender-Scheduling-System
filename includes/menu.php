<?php
session_start();
?>
<link rel="stylesheet" href="../assets/css/menu.css">

<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>

            <?php if (isset($_SESSION['ID']) && $_SESSION['is_admin'] == true): ?>
                <li><a href="UserManagment.php">User Managment</a></li>
                <li><a href="Dashboard.php">Dashboard</a></li>
                <li><a href="Taskmanag_admin.php">Task Management</a></li>
                <li><a href="Logout.php">Logout</a></li>

            <?php elseif (isset($_SESSION['ID']) && $_SESSION['is_admin'] == false): ?>
                <li><a href="Dashboard.php">Dashboard</a></li>
                <li><a href="Taskmanag.php">Task Management</a></li>
                <li><a href="Profile.php">Profile</a></li>
                <li><a href="Logout.php">Logout</a></li>

            <?php else: ?>
                <li><a href="Login.php">Login</a></li>
                <li><a href="SignUp.php">Signup</a></li>
            <?php endif; ?>
            
            <li><a href="index.php#About">About</a></li>
            <li><a href="index.php#contact" class="button">Contact us</a></li>
        </ul>
    </nav>
</header>
