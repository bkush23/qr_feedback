<?php
require_once 'db.php';
include('header.php');

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["company_id"])) {
    header("location: company_login.php");
    exit;
}

$company_id = $_SESSION["company_id"];
$update_success = false;
$error_message = '';

// Fetch current company details to pre-fill the form
$sql = "SELECT company_name, company_email, company_contact_no, company_category, company_address, company_website FROM credentials WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $company_id);
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($company_name, $company_email, $company_contact_no, $company_category, $company_address, $company_website);
            $stmt->fetch();
        } else {
            $error_message = "Could not find company details.";
        }
    }
    $stmt->close();
}


// Handle form submission for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $new_company_name = trim($_POST['company_name']);
    $new_company_contact_no = trim($_POST['company_contact_no']);
    $new_company_category = trim($_POST['company_category']);
    $new_company_address = trim($_POST['company_address']);
    $new_company_website = trim($_POST['company_website']);

    // Prepare an update statement
    $sql = "UPDATE credentials SET company_name = ?, company_contact_no = ?, company_category = ?, company_address = ?, company_website = ? WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssi", $new_company_name, $new_company_contact_no, $new_company_category, $new_company_address, $new_company_website, $company_id);

        if ($stmt->execute()) {
            $update_success = true;
            // Refresh the data to show the updated values in the form
            $company_name = $new_company_name;
            $company_contact_no = $new_company_contact_no;
            $company_category = $new_company_category;
            $company_address = $new_company_address;
            $company_website = $new_company_website;
            
            // Also update the session variable for the company name
            $_SESSION['company_name'] = $new_company_name;

        } else {
            $error_message = "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}


$conn->close();
?>

<main class="container">
    <div class="form-wrapper">
        <div class="form-container">
            <div class="page-header">
                <h1 class="page-title">Update Your Profile</h1>
                <p>Edit the details below to update your company profile.</p>
            </div>

            <?php if ($update_success): ?>
                <div class="alert alert-success">Profile updated successfully!</div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="styled-form">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control" value="<?= htmlspecialchars($company_name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="company_email">Company Email</label>
                    <input type="email" name="company_email" id="company_email" class="form-control" value="<?= htmlspecialchars($company_email); ?>" disabled>
                     <small class="form-text">Email cannot be changed.</small>
                </div>
                <div class="form-group">
                    <label for="company_contact_no">Contact Number</label>
                    <input type="text" name="company_contact_no" id="company_contact_no" class="form-control" value="<?= htmlspecialchars($company_contact_no); ?>" required>
                </div>
                <div class="form-group">
                    <label for="company_category">Category</label>
                    <select name="company_category" id="company_category" class="form-control" required>
                        <option value="Tech" <?= ($company_category === 'Tech') ? 'selected' : ''; ?>>Tech</option>
                        <option value="Finance" <?= ($company_category === 'Finance') ? 'selected' : ''; ?>>Finance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="company_address">Address</label>
                    <input type="text" name="company_address" id="company_address" class="form-control" value="<?= htmlspecialchars($company_address); ?>" required>
                </div>
                <div class="form-group">
                    <label for="company_website">Website</label>
                    <input type="url" name="company_website" id="company_website" class="form-control" value="<?= htmlspecialchars($company_website); ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php
include('footer.php');
?>
