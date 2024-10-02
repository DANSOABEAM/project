<?php
// Backend: Connect to the database and fetch the attendance records
$host = "localhost";
$username = "root";
$password = "";
$database = "mme_micro_credit";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch attendance records including employee details
$attendance_query = "
    SELECT e.id, e.first_name, e.last_name, e.profile_picture, a.date, a.status
    FROM attendance a
    JOIN employees e ON a.employee_id = e.id
    ORDER BY e.id, a.date ASC";
$attendance_result = $conn->query($attendance_query);

// Query to count the number of days worked (where status is 'Present') per employee
$days_worked_query = "
    SELECT employee_id, COUNT(*) AS days_worked 
    FROM attendance 
    WHERE status = 'Present'
    GROUP BY employee_id";
$days_worked_result = $conn->query($days_worked_query);

// Store the days worked in an associative array
$days_worked = [];
while ($row = $days_worked_result->fetch_assoc()) {
    $days_worked[$row['employee_id']] = $row['days_worked'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Records</title>
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













    <div class="container mt-5">
        <h2 class="text-center">Employee Attendance Records</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee Image</th>
                    <th>Employee Name</th>
                    <th>Attendance Date</th>
                    <th>Status</th>
                    <th>Days Worked</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $attendance_result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="uploads/<?php echo htmlspecialchars($row['profile_picture']); ?>" class="employee-image" alt="Employee Image">
                        </td>
                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo isset($days_worked[$row['id']]) ? $days_worked[$row['id']] : 0; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <span>&copy; DandS Francis Kwakye 2024</SPAN>
</body>
</html>

<?php
$conn->close();
?>
