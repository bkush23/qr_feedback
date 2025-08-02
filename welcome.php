<?php
include('header.php');

if (!isset($_SESSION['company_name'])) {
    header("Location: company_login.php");
    exit;
}
?>

<main class="container">
    <div class="text-center" style="padding: 4rem 0;">
        <h1 class="page-title">Welcome, <?= htmlspecialchars($_SESSION['company_name']) ?>!</h1>
        <p style="font-size: 1.25rem; color: var(--text-light);">You are successfully logged in.</p>
        <a href="event_list.php" class="btn btn-primary" style="margin-top: 2rem;">View Your Events</a>
    </div>
</main>

<?php
include('footer.php');
?>
