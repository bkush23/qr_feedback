<?php
require_once 'db.php';
include('header.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['company_email'];
    $password = $_POST['company_password'];

    $sql = "SELECT id, company_name, company_password FROM credentials WHERE company_email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Use password_verify() as it is the standard for hashed passwords.
        // This is the most likely correct implementation.
        if (password_verify($password, $row['company_password'])) {
            $_SESSION['company_id'] = $row['id'];
            $_SESSION['company_name'] = $row['company_name'];
            header("Location: create_event.php"); // Redirect to create_event page after login
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<main class="container">
    <div class="form-wrapper">
        <div class="form-container">
            <h2 class="page-title">Company Login</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="company_email">Email</label>
                    <input id="company_email" type="email" name="company_email" class="form-control" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="company_password">Password</label>
                    <input id="company_password" type="password" name="company_password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p class="form-text"><a href="forgot_password.php">Forgot Password?</a></p>
                <p class="form-text">Don't have an account? <a href="company_register.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</main>

<?php
include('footer.php');
?>
