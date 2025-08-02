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

// Verify that the event belongs to the logged-in company and get event name
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

// Fetch feedback for the event
$stmt = $conn->prepare('SELECT rating, comments, created_at FROM feedback WHERE event_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $event_id);
$stmt->execute();
$feedback_result = $stmt->get_result();

$feedback_entries = [];
if ($feedback_result) {
    while ($row = $feedback_result->fetch_assoc()) {
        $feedback_entries[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback for <?= htmlspecialchars($event_name) ?> - QR Feedback</title>
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
        <h2 class="page-title">Feedback for "<?= htmlspecialchars($event_name) ?>"</h2>

        <div class="feedback-list">
            <?php if (empty($feedback_entries)): ?>
                <p>No feedback has been submitted for this event yet.</p>
            <?php else: ?>
                <?php foreach ($feedback_entries as $feedback): ?>
                    <div class="feedback-card">
                        <p><strong>Rating:</strong> <?= htmlspecialchars($feedback['rating']) ?>/5</p>
                        <p><strong>Comments:</strong> <?= nl2br(htmlspecialchars($feedback['comments'])) ?></p>
                        <p><em>Submitted on: <?= date('F j, Y, g:i a', strtotime($feedback['created_at'])) ?></em></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> QR Feedback. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
