<?php
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

// Fetch data for the dashboard
$employee_count_result = $conn->query("SELECT COUNT(*) AS count FROM employees");
$employee_count = $employee_count_result->fetch_assoc()['count'];

$pending_leave_count_result = $conn->query("SELECT COUNT(*) AS count FROM leave_requests WHERE status = 'pending'");
$pending_leave_count = $pending_leave_count_result->fetch_assoc()['count'];

// Add any other data fetching queries as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="dashboard">
            <!-- Data Cards -->
            <div class="card">
                <h2>Employees</h2>
                <p><?= $employee_count; ?></p>
                <i class="fas fa-users"></i>
            </div>
            <div class="card">
                <h2>Pending Leave Requests</h2>
                <p><?= $pending_leave_count; ?></p>
                <i class="fas fa-calendar-alt"></i>
            </div>

            <!-- Add more cards as needed -->
        </div>

        <!-- Navigation Links to Important Pages -->
        <div class="links">
            <a href="leave_apply.php">Leave Application <i class="fas fa-users"></i></a><br>
            <a href="add_employee.php">Add Employee <i class="fas fa-calendar-alt"></i></a><br>
            <a href="attendance.php">Attendance <i class="fas fa-money-check-alt"></i></a><br>
            <a href="view_employee.php">View Employees <i class="fas fa-clock"></i></a><br>
            <a href="reporting.php">View Reports <i class="fas fa-chart-line"></i></a><br>
            <a href="leave.php">Leave Management <i class="fas fa-chart-line"></i></a><br>
            <a href="payroll.php">Payroll <i class="fas fa-chart-line"></i></a><br>
            <a href="employee_payroll.php">Employee Payroll <i class="fas fa-chart-line"></i></a><br>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
