<?php
include 'header.php'; // Contains session_start() and page styling

// Check if the user is logged in. If not, redirect to the login page and STOP.
if (!isset($_SESSION['company_id'])) {
    header('Location: company_login.php');
    exit(); // Stop all further script execution
}

// Only proceed if the user is logged in.
$conn = include "connection.php";
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($conn) {
        // Ensure all POST keys exist before accessing them
        $eventName = isset($_POST['event_name']) ? $_POST['event_name'] : '';
        $eventDate = isset($_POST['event_date']) ? $_POST['event_date'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : ''; // Check for description
        $companyId = $_SESSION['company_id']; // The correct session variable from the now-fixed login

        // The query MUST match the database schema from db.sql
        $stmt = $conn->prepare("INSERT INTO events (event_name, event_date, description, company_id) VALUES (?, ?, ?, ?)");
        
        if ($stmt) {
            $stmt->bind_param("sssi", $eventName, $eventDate, $description, $companyId);

            if ($stmt->execute()) {
                $message = "Event created successfully!";
            } else {
                // Provide a more detailed error message for debugging
                $message = "Error creating event: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            $message = "Error preparing statement: " . htmlspecialchars($conn->error);
        }
        $conn->close();
    } else {
        $message = "Error: Database connection failed.";
    }
}
?>

<div class="container">
    <h2>Create New Event</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info">
            <?php echo $message; // Already escaped ?>
        </div>
    <?php endif; ?>
    <form action="create_event.php" method="post">
        <div class="form-group">
            <label for="event_name">Event Name:</label>
            <input type="text" class="form-control" id="event_name" name="event_name" required>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date:</label>
            <input type="date" class="form-control" id="event_date" name="event_date" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>

<?php
include 'footer.php';
?>
