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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .employee-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-nav a {
            color: #343a40;
        }
        .navbar-nav a:hover {
            color: #007bff;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="leave_apply.php">Leave Application <i class="fas fa-users"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="add_employee.php">Add Employee <i class="fas fa-calendar-alt"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="attendance.php">Attendance <i class="fas fa-money-check-alt"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="view_employee.php">View Employees <i class="fas fa-clock"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="reporting.php">View Reports <i class="fas fa-chart-line"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="leave.php">Leave Management <i class="fas fa-chart-line"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="payroll.php">Payroll <i class="fas fa-chart-line"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="employee_payroll.php">Employee Payroll <i class="fas fa-chart-line"></i></a></li>
        <li class="nav-item"><a class="nav-link" href="employee_attendance.php">Employee Attendance Record <i class="fas fa-chart-line"></i></a></li>
    </ul>
</nav>

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

<!-- Modal for detailed attendance info -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Attendance Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Attendance details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5">
    <span>&copy; DandS Francis Kwakye 2024</span>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Example of loading data into modal (this is just a placeholder, adjust as needed)
    $('#attendanceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var employeeId = button.data('employee-id'); // Extract info from data-* attributes
        // Use AJAX to fetch details from server (placeholder code)
        var modal = $(this);
        modal.find('.modal-body').text('Attendance details for employee ID: ' + employeeId);
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
