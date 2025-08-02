<?php
require_once 'db.php';
include('header.php');

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $company_email = $_POST['company_email'];
    $company_password = password_hash($_POST['company_password'], PASSWORD_DEFAULT);
    $company_contact_no = $_POST['company_contact_no'];
    $company_category = $_POST['company_category'];
    $company_address = $_POST['company_address'];
    $company_website = $_POST['company_website'];

    // Check if email already exists
    $check_sql = "SELECT id FROM credentials WHERE company_email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('s', $company_email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Email ID already registered.";
    } else {
        $sql = "INSERT INTO credentials (company_name, company_email, company_password, company_contact_no, company_category, company_address, company_website) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssss', $company_name, $company_email, $company_password, $company_contact_no, $company_category, $company_address, $company_website);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

<main class="container">
    <div class="form-wrapper">
        <div class="form-container">
            <h2 class="page-title">Company Registration</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="#">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter company name" required />
                </div>
                <div class="form-group">
                    <label for="company_email">Email</label>
                    <input type="email" id="company_email" name="company_email" class="form-control" placeholder="you@example.com" required />
                </div>
                <div class="form-group">
                    <label for="company_password">Password</label>
                    <input type="password" id="company_password" name="company_password" class="form-control" placeholder="••••••••" required />
                </div>
                <div class="form-group">
                    <label for="company_contact_no">Contact Number</label>
                    <input type="text" id="company_contact_no" name="company_contact_no" class="form-control" placeholder="e.g., +1-555-555-5555" required />
                </div>
                <div class="form-group">
                    <label for="company_category">Category</label>
                    <select id="company_category" name="company_category" class="form-control" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Tech">Tech</option>
                        <option value="Finance">Finance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="company_address">Address</label>
                    <input type="text" id="company_address" name="company_address" class="form-control" placeholder="123 Main St, Anytown, USA" required />
                </div>
                <div class="form-group">
                    <label for="company_website">Website</label>
                    <input type="text" id="company_website" name="company_website" class="form-control" placeholder="www.example.com" required />
                    <span id="website_status" class="error-message" style="display: none; margin-top: 0.5rem;"></span>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
                 <p class="form-text">Already have an account? <a href="company_login.php">Login</a></p>
            </form>
        </div>
    </div>
</main>

<script>
    const websiteInput = document.getElementById('company_website');
    const websiteStatus = document.getElementById('website_status');

    websiteInput.addEventListener('blur', () => {
        const url = websiteInput.value;
        if (url) {
            let fullUrl = url;
            if (!fullUrl.startsWith('http://') && !fullUrl.startsWith('https://')) {
                fullUrl = 'https://' + url;
            }
            websiteStatus.style.display = 'none';
            fetch(fullUrl, { mode: 'no-cors' })
                .then(() => {
                    websiteStatus.style.display = 'none';
                })
                .catch(() => {
                    websiteStatus.textContent = 'Website is not working';
                    websiteStatus.style.display = 'block';
                });
        }
    });
</script>

<?php
include('footer.php');
?>
