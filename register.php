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

// Check if an admin already exists
$admin_check_query = "SELECT COUNT(*) as admin_count FROM admins";
$admin_check_result = $conn->query($admin_check_query);
$row = $admin_check_result->fetch_assoc();

if ($row['admin_count'] > 0) {
    echo "<h3>Admin account already exists. You cannot register a new admin.</h3>";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if ($password !== $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match!</p>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Admin registered successfully!</p>";
            header("Location: login.php"); // Redirect to login page after registration
            exit();
        } else {
            echo "<p style='color:red;'>Error: Could not register admin.</p>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="con">
        <div class="con1">
            <h2>Admin Registration</h2>
            <form action="" method="POST">
                <label for="username">Username:</label><br> 
                <input type="text" id="username" name="username" required><br><br>

                <label for="email">Email:</label><br> 
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label><br> 
                <input type="password" id="password" name="password" required><br><br>

                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br><br>

                <input type="submit" value="Register" id="btn1">
            </form>
        </div>
    </div>
</body>
</html>
