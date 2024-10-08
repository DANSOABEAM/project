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

// Handle report generation
$report_type = "";
$start_date = "";
$end_date = "";
$report_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if ($report_type == 'attendance') {
        $sql = "SELECT employees.first_name, employees.last_name, attendance.date, attendance.status
                FROM attendance 
                JOIN employees ON attendance.employee_id = employees.id
                WHERE attendance.date BETWEEN '$start_date' AND '$end_date'";
        $report_data = $conn->query($sql);
    } elseif ($report_type == 'payroll') {
        $sql = "SELECT employees.first_name, employees.last_name, payroll.total_pay, payroll.pay_date 
                FROM payroll 
                JOIN employees ON payroll.employee_id = employees.id
                WHERE payroll.pay_date BETWEEN '$start_date' AND '$end_date'";
        $report_data = $conn->query($sql);
    } elseif ($report_type == 'leave') {
        $sql = "SELECT employees.first_name, employees.last_name, leave_requests.leave_type, leave_requests.start_date, leave_requests.end_date, leave_requests.status
                FROM leave_requests 
                JOIN employees ON leave_requests.employee_id = employees.id
                WHERE leave_requests.start_date BETWEEN '$start_date' AND '$end_date'";
        $report_data = $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting</title>
   <link rel="stylesheet" href="nav.css">
    <script>
        function showLoading() {
            document.getElementById("loading-animation").style.display = "block";
        }
    </script>
      <link rel="stylesheet" href="leave_apply.css">
      <link rel="stylesheet" href="leave.css">
      <link rel="stylesheet" href="nav.css">

<style>
.container{
   display:flex;
   align-items:centre;
  
   justify-content:centre;
}
.form-group{
   
    width:250%;
    margin-left:20%;
    margin:7px;
}
img{
    width:30%;
    margin-left:5%;
}
.form-control{
    width:110%;
    margin-left:30%;
    
}
.btn{
    width:70%;
    margin-left:80%;
}
table{
    width:100%;
}
</style>
</head>
<body>
<ul>
    <li>   <a href="leave_apply.php">Leave Application <i class="fas fa-users"></i></a></li> 
    <li>  <a href="add_employee.php">Add Employee <i class="fas fa-calendar-alt"></i></a></li> 
    <li> <a href="attendance .php">Attendance <i class="fas fa-money-check-alt"></i></a></li> 
    <li>   <a href="view_emplyee.php">All Employees <i class="fas fa-clock"></i></a></li> 
    <li>  <a href="reporting.php"> Reports <i class="fas fa-chart-line"></i></a></li> 
    <li> <a href="leave.php">Leave Management <i class="fas fa-chart-line"></i></a>
        <li> <a href="payroll.php">Payroll <i class="fas fa-chart-line"></i></a></li> 
        <li> <a href="employee_payroll.php">Employee Payroll <i class="fas fa-chart-line"></i></a><br>
            <li> <a href="employee_attendance.php">employee attendance record <i class="fas fa-chart-line"></i></a><br>

    </ul>


<div class="container">
<img src="Screenshot 2024-10-02 121004.png" width="50%"/>

   

    <!-- Report Filters Form -->
    <form method="POST" action="" onsubmit="showLoading()">
        <div class="form-group">
        <h2>Generate Reports</h2>
            <label for="report_type">Report Type:</label><br><br>
            <select name="report_type" id="report_type" class="form-control" required><br><br>
                <option value="attendance">Attendance Report</option>
                <option value="payroll">Payroll Report</option>
                <option value="leave">Leave Report</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date:</label><br><br>
            <input type="date" name="start_date" id="start_date" class="form-control" required><br><br>
        </div>

        <div class="form-group">
            <label for="end_date">End Date:</label><br><br>
            <input type="date" name="end_date" id="end_date" class="form-control" required><br><br>
        </div>

        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>
    </div>
   

    <!-- Report Table -->
    <?php if ($report_data && $report_data->num_rows > 0): ?>
        <table class="table1 table-bordered">
            <thead>
                <tr>
                    <?php if ($report_type == 'attendance'): ?>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    <?php elseif ($report_type == 'payroll'): ?>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Total Pay</th>
                        <th>Pay Date</th>
                    <?php elseif ($report_type == 'leave'): ?>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $report_data->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['first_name']; ?></td>
                        <td><?= $row['last_name']; ?></td>
                        <?php if ($report_type == 'attendance'): ?>
                            <td><?= $row['date']; ?></td>
                            <td><?= $row['status']; ?></td>
                        <?php elseif ($report_type == 'payroll'): ?>
                            <td>$<?= number_format($row['total_pay'], 2); ?></td>
                            <td><?= $row['pay_date']; ?></td>
                        <?php elseif ($report_type == 'leave'): ?>
                            <td><?= $row['leave_type']; ?></td>
                            <td><?= $row['start_date']; ?></td>
                            <td><?= $row['end_date']; ?></td>
                            <td><?= $row['status']; ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No records found for the selected report type and date range.</p>
    <?php endif; ?>



</body>
</html>

<?php
$conn->close();
?>
