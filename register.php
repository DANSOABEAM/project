<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";  // Replace with your database password
$dbname = "mme_micro_credit";  // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $admin_username = $_POST['username'];
    $admin_email = $_POST['email'];
    $admin_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data
    if ($admin_password != $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match!</p>";
    } else {
        // Hash the password
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo "<h4 style='color:red;'>Email is already registered!</h4>";
        } else {
            // Insert the new admin into the database
            $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $admin_username, $admin_email, $hashed_password);

            if ($stmt->execute()) {
                echo "<h4 style='color:green;'>Registration successful!</h4>";
            } else {
                echo "<h4 style='color:red;'>Error: " . $stmt->error . "</h4>";
            }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
 integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
 crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container">
    <div class="container1">
    <h2>Admin Registration   </h2>
    <form action="" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" value="Register" class="btn"> 
        <h6>already have an account? <a href="login.php">login</a><h6>
        <a href="mailto:francisdanso978@gmail.com" id="a2">developer <i class="fas fa-envelope"></i></a>
    </form>
  
</div>
</div>

</body>
</html>
