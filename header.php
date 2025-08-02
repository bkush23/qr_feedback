<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Feedback System</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
</head>
<body>

    <header class="header">
        <div class="container nav-container">
            <div class="logo">
                <a href="welcome.php">QR Feedback</a>
            </div>
            <nav class="nav-links">
                <?php if (isset($_SESSION['company_name'])): ?>
                    <a href="event_list.php">Events</a>
                    <a href="update_profile.php">Update Profile</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="company_login.php">Login</a>
                    <a href="company_register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
