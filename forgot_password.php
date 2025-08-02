<?php
require_once 'db.php';
session_start();

$message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_email = trim($_POST['company_email']);

    if (!empty($company_email) && filter_var($company_email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT id FROM credentials WHERE company_email = ?");
        $stmt->bind_param("s", $company_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "If an account with that email exists, a password reset link has been sent.";
        } else {
            $message = "If an account with that email exists, a password reset link has been sent.";
        }
        $stmt->close();
    } else {
        $error_message = "Please enter a valid email address.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - QR Feedback System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

<header class="header">
    <div class="nav-container container">
        <div class="logo">
            <a href="welcome.php">QR Feedback System</a>
        </div>
        <nav class="nav-links">
            <a href="company_login.php">Login</a>
            <a href="company_register.php">Register</a>
        </nav>
    </div>
</header>

<main>
    <div class="form-wrapper">
        <div class="form-container">
            <h2>Forgot Your Password?</h2>
            <p style="text-align: center; margin-bottom: 2rem; color: var(--text-light);">Enter your email, and we'll send you a reset link.</p>

            <?php if (!empty($message)): ?>
                <div class="success-message"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="company_email">Company Email</label>
                    <input type="email" name="company_email" id="company_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-container container">
        <p>&copy; <?php echo date("Y"); ?> QR Feedback System. All rights reserved.</p>
        <div class="footer-links">
            <a href="company_login.php">Login</a>
            <a href="company_register.php">Register</a>
        </div>
    </div>
</footer>

</body>
</html>
