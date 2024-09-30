<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";  // Your database password
$dbname = "mme_micro_credit";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch admin
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // If the admin is found
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $admin_username, $hashed_password);
        $stmt->fetch();
        
        // Verify the entered password with the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables and redirect to dashboard
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_username'] = $admin_username;
            header("Location: dashboard.php"); // Redirect to the admin dashboard
            exit();
        } else {
            echo "<p style='color:red;'>Incorrect password!</p>";
        }
    } else {
        echo "<p style='color:red;'>No admin found with that email!</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="register.css">

</head>
<body>
    <p></p>
    <p></p>
    <b></b>
    <div class="con">
        <div class="con1">
    <h2>Admin Login</h2>
    <form action="" method="POST">
        <label for="email">Email:</label><br> 
        <input type="email" id="email1" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password1" name="password" required><br><br>

        <input type="submit" value="Login" id="btn1">
    </form>
    
</div>
</div>
</body>
</html>
