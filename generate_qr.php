<?php
session_start();
include 'db.php';

if (!isset($_SESSION['company_id'])) {
    header('Location: company_login.php');
    exit();
}

if (!isset($_GET['event_id'])) {
    header('Location: event_list.php');
    exit();
}

$event_id = $_GET['event_id'];
$company_id = $_SESSION['company_id'];

// Verify that the event belongs to the logged-in company
$stmt = $conn->prepare('SELECT event_name FROM events WHERE id = ? AND company_id = ?');
$stmt->bind_param('ii', $event_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    // Event not found or doesn't belong to the company
    header('Location: event_list.php');
    exit();
}

$event_name = $event['event_name'];

// Generate the URL for the feedback form
$feedback_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/feedback_form.php?event_id=" . $event_id;

// Use a QR code generation API
$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($feedback_url);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code for <?= htmlspecialchars($event_name) ?> - QR Feedback</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <h1><a href="welcome.php">QR Feedback</a></h1>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="event_list.php">Events</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <h2 class="page-title">QR Code for "<?= htmlspecialchars($event_name) ?>"</h2>
        <div style="text-align: center; margin-top: 2rem;">
            <img src="<?= $qr_code_url ?>" alt="QR Code for <?= htmlspecialchars($event_name) ?>">
            <p style="margin-top: 1rem;">Scan this QR code to provide feedback for the event.</p>
            <p><a href="<?= $feedback_url ?>" target="_blank">Or click here to open the feedback form directly.</a></p>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> QR Feedback. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
