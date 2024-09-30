<?php
// Start session to check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "mme_micro_credit"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch employee details
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];
    $sql = "SELECT * FROM employees WHERE id = $employee_id";
    $result = $conn->query($sql);
    $employee = $result->fetch_assoc();
} else {
    header("Location: view_employee.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $hire_date = $_POST['hire_date'];
    $profile_picture = $_FILES['profile_picture']['name'];

    // Handle file upload if a new picture is provided
    if ($profile_picture) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "uploads/" . $profile_picture);
        $sql_update = "UPDATE employees SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', department='$department', hire_date='$hire_date', profile_picture='uploads/$profile_picture' WHERE id=$employee_id";
    } else {
        $sql_update = "UPDATE employees SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', department='$department', hire_date='$hire_date' WHERE id=$employee_id";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Employee updated successfully.'); window.location.href='view_employees.php';</script>";
    } else {
        echo "Error updating employee: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
  
    <script>
        function showLoading() {
            document.getElementById("loading").style.display = "block";
        }
    </script>
    <link rel="stylesheet" href="leave_apply.css">
    <link rel="stylesheet" href="nav.css">
</head>
<body>
<ul>
    <li>   <a href="leave_apply.php">Leave Application <i class="fas fa-users"></i></a></li> 
    <li>  <a href="add_employee.php">Add Employee <i class="fas fa-calendar-alt"></i></a></li> 
    <li> <a href="attendance .php">Attendance <i class="fas fa-money-check-alt"></i></a></li> 
    <li>   <a href="view_emplyee.php">View Employees <i class="fas fa-clock"></i></a></li> 
    <li>  <a href="reporting.php">View Reports <i class="fas fa-chart-line"></i></a></li> 
    <li> <a href="leave.php">Leave Management <i class="fas fa-chart-line"></i></a>
        <li> <a href="payroll.php">Payroll <i class="fas fa-chart-line"></i></a></li> 
        <li> <a href="employee_payroll.php">Employee Payroll <i class="fas fa-chart-line"></i></a><br>
            <li> <a href="employee_attendance.php">employee attendance record <i class="fas fa-chart-line"></i></a><br>

    </ul>









    <div class="container">
        <h2 class="text-center">Edit Employee</h2>
        <form method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
        <small class="form-text text-muted">
                 
                 <img src="<?= htmlspecialchars($employee['profile_picture']); ?>" alt="Profile Picture" style="width: 100px; height: 100px;">
             </small>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($employee['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($employee['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employee['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($employee['department']); ?>" required>
            </div>
            <div class="form-group">
                <label for="hire_date">Hire Date:</label>
                <input type="date" name="hire_date" class="form-control" value="<?= htmlspecialchars($employee['hire_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" class="form-control-file">
              
            </div>
            <button type="submit" class="btn btn-primary">Update Employee</button>
      
        </form>
        
</body>
</html>
