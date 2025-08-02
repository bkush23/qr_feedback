<?php
include 'db.php';

$message = '';
$error = '';
$event_name = '';

if (!isset($_GET['event_id'])) {
    $error = 'No event specified.';
} else {
    $event_id = $_GET['event_id'];

    // Fetch event details
    $stmt = $conn->prepare('SELECT event_name FROM events WHERE id = ?');
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    if (!$event) {
        $error = 'Event not found.';
    } else {
        $event_name = $event['event_name'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($error)) {
        $rating = $_POST['rating'];
        $comments = $_POST['comments'];

        if (empty($rating)) {
            $message = 'Please provide a rating.';
        } else {
            $stmt = $conn->prepare('INSERT INTO feedback (event_id, rating, comments) VALUES (?, ?, ?)');
            $stmt->bind_param('iis', $event_id, $rating, $comments);

            if ($stmt->execute()) {
                $message = 'Thank you for your feedback!';
            } else {
                $error = 'Error submitting feedback. Please try again.';
            }
        }
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
                <h1><a href="index.php">QR Feedback</a></h1>
            </div>
        </div>
    </header>

    <div class="form-wrapper">
        <div class="form-container">
            <?php if (!empty($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php else: ?>
                <h2 class="page-title">Feedback for "<?= htmlspecialchars($event_name) ?>"</h2>
                <?php if (!empty($message) && $message === 'Thank you for your feedback!'): ?>
                    <div class="success-message"><?= $message ?></div>
                <?php else: ?>
                    <?php if (!empty($message)): ?>
                        <div class="error-message"><?= $message ?></div>
                    <?php endif; ?>
                    <form action="feedback_form.php?event_id=<?= $event_id ?>" method="post">
                        <div class="form-group">
                            <label for="rating">Rating (1-5)</label>
                            <input type="number" id="rating" name="rating" class="form-control" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn">Submit Feedback</button>
                        </div>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> QR Feedback. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
