<?php
require_once 'db.php';
include('header.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["company_id"])) {
    header("location: company_login.php");
    exit;
}

$company_id = $_SESSION["company_id"];
$events = [];

// Fetch events for the logged-in company
$sql = "SELECT id, event_name, event_date, description FROM events WHERE company_id = ? ORDER BY event_date DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $company_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    } else {
        error_log("Error executing statement: " . $stmt->error);
    }
    $stmt->close();
} else {
    error_log("Error preparing statement: " . $conn->error);
}

$conn->close();
?>

<main class="container">
    <div class="page-header">
        <h1 class="page-title">Your Events</h1>
        <a href="create_event.php" class="btn btn-primary">Create New Event</a>
    </div>

    <?php if (empty($events)): ?>
        <div class="card text-center" style="padding: 4rem;">
            <h2>No events found.</h2>
            <p style="color: var(--text-light); margin-top: 1rem;">Click the "Create New Event" button to get started.</p>
        </div>
    <?php else: ?>
        <div class="event-grid">
            <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($event['event_name']) ?></h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Date:</strong> <?= date("F j, Y", strtotime($event['event_date'])) ?></p>
                        <p>
                            <?php
                            if (!empty($event['description'])) {
                                echo htmlspecialchars($event['description']);
                            } else {
                                echo '<span style="color: var(--text-light);">No description provided.</span>';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="generate_qr.php?id=<?= $event['id'] ?>" class="btn btn-primary">Generate QR</a>
                        <a href="view_feedback.php?id=<?= $event['id'] ?>" class="btn btn-secondary">View Feedback</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php
include('footer.php');
?>
