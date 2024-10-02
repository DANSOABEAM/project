<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";  // Your database password
$dbname = "mme_micro_credit";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$step = 1; // Initial step is to enter email
$error_message = "";
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        // Step 1: Check email existence
        $email = $_POST['email'];
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email exists, move to the next step (password reset)
            $step = 2;
        } else {
            $error_message = "No admin found with that email!";
        }
        $stmt->close();
    } elseif (isset($_POST['new_password'], $_POST['confirm_password'])) {
        // Step 2: Reset password
        $email = $_POST['hidden_email'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $success_message = "Password reset successfully!";
                $step = 3; // Success message step
            } else {
                $error_message = "Error resetting password.";
            }

            $stmt->close();
        } else {
            $error_message = "Passwords do not match!";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password</title>
   <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error"><?= $error_message ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p class="success"><?= $success_message ?></p>
    <?php endif; ?>

    <?php if ($step == 1): ?>
        <!-- Step 1: Enter email to reset password -->
        <form action="" method="POST">
            <label for="email">Enter Your Email:</label>
            <input type="email" name="email" required>
            <input type="submit" value="Submit">
        </form>

    <?php elseif ($step == 2): ?>
        <!-- Step 2: Reset password form -->
        <form action="" method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" required>

            <input type="hidden" name="hidden_email" value="<?= $email ?>">
            <input type="submit" value="Reset Password">
        </form>
        
    <?php elseif ($step == 3): ?>
        <!-- Step 3: Success message -->
        <p class="success">Password has been reset successfully! You can now <a href="login.php">login</a> with your new password.</p>
    <?php endif; ?>

</div>

</body>
</html>
