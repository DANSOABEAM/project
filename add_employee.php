<?php
// Start session to check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get employee data from the form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $hire_date = $_POST['hire_date'];

    // Handling file uploads
    $upload_dir = "uploads/";  // Directory to store the files

    // Handle profile picture
    $profile_picture_name = $_FILES['profile_picture']['name'];
    $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_path = $upload_dir . basename($profile_picture_name);

    // Handle CV document
    $cv_document_name = $_FILES['cv_document']['name'];
    $cv_document_tmp = $_FILES['cv_document']['tmp_name'];
    $cv_document_path = $upload_dir . basename($cv_document_name);

    // Check if the files were uploaded
    if (move_uploaded_file($profile_picture_tmp, $profile_picture_path) && move_uploaded_file($cv_document_tmp, $cv_document_path)) {
        // Insert employee data into the database
        $stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, email, phone, department, hire_date, profile_picture, cv_document) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $first_name, $last_name, $email, $phone, $department, $hire_date, $profile_picture_path, $cv_document_path);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Employee added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;'>Error uploading files!</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link rel="stylesheet" href="add_employee.css">
    
</head>
<body>
 <fieldset>

    <legend>Add Emloyees</legend>
                <div class="main">
                        <div class="one">
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="first_name">First Name:</label><br>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Last Name:</label><br>
        <input type="text" id="last_name" name="last_name" required><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" maxlength="10" minlength="10"><br>
</div>

<div class="two">

        <label for="department">Department:</label><br>
        <input type="text" id="department" name="department"><br>

        <label for="hire_date">Hire Date:</label><br>
        <input type="date" id="hire_date" name="hire_date" required><br><br>

        <!-- Profile Picture Upload -->
      
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required><br>

        <!-- CV Document Upload -->
      
        <input type="file" id="cv_document" name="cv_document" accept=".pdf" required><br>
</div>
        </div>
        <input type="submit" value="Add Employee" class="btn">
    </form>
<br>
<p>

</p>
<p></p>
<p></p><br><br><br><br><br>
</fieldset>

</body>
</html>
