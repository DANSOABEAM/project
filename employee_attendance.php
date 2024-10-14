<?php
// Database connection
$servername = "localhost";
$username = "root"; // Update with your database username
$password = ""; // Update with your database password
$dbname = "mme_micro_credit"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all employees for the dropdown
$employees_sql = "SELECT id, first_name, last_name FROM employees";
$employees_result = $conn->query($employees_sql);

// Check for errors
if (!$employees_result) {
    die("Error fetching employees: " . $conn->error);
}

// Initialize variables for attendance query
$attendance_result = null;
$employee_name = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Fetch employee name
    $employee_sql = "SELECT first_name, last_name FROM employees WHERE id = '$employee_id'";
    $employee_result = $conn->query($employee_sql);

    // Check for errors
    if (!$employee_result) {
        die("Error fetching employee: " . $conn->error);
    }

    $employee_data = $employee_result->fetch_assoc();
    if ($employee_data) {
        $employee_name = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
    } else {
        die("Employee not found.");
    }

    // Fetch attendance records for the selected employee within the date range
    $attendance_sql = "SELECT * FROM attendance WHERE employee_id = '$employee_id' 
                       AND date BETWEEN '$start_date' AND '$end_date'";
    $attendance_result = $conn->query($attendance_sql);

    // Check for errors
    if (!$attendance_result) {
        die("Error fetching attendance records: " . $conn->error);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        h2 {
            margin-bottom: 15px;
            font-size:15px;
            text-align:centre;
            margin-left:40%;
        }

        @media print {
            #printBtn {
                display: none; /* Hide print button when printing */
            }
        }
        a{
            font-size:13px;
        }
        .btn{
            font-size:12px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">MME Micro Credit</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="leave_apply.php">Leave Application</a></li>
            <li class="nav-item"><a class="nav-link" href="add_employee.php">Add Employee</a></li>
            <li class="nav-item"><a class="nav-link" href="attendance.php">Attendance</a></li>
            <li class="nav-item"><a class="nav-link" href="view_employee.php">All Employees</a></li>
            <li class="nav-item"><a class="nav-link" href="reporting.php">Reports</a></li>
            <li class="nav-item"><a class="nav-link" href="leave.php">Leave Management</a></li>
            <li class="nav-item"><a class="nav-link" href="payroll.php">Payroll</a></li>
            <li class="nav-item"><a class="nav-link" href="employee_payroll.php">Employee Payroll</a></li>
            <li class="nav-item"><a class="nav-link" href="employee_attendance.php">Employee Attendance Record</a></li>
        </ul>
    </div>
</nav>
<hr>
<div class="container">
    <h2>View Employee Attendance Records</h2>
    <form method="POST" action="" class="mb-4">
        <div class="form-group">
            <label for="employee_id">Select Employee:</label>
            <select name="employee_id" id="employee_id" class="form-control" required>
                <option value="">-- Select an Employee --</option>
                <?php while ($row = $employees_result->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['id']); ?>">
                        <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">View Attendance</button>
    </form>

    <?php if ($attendance_result && $attendance_result->num_rows > 0): ?>
        <!-- Trigger button for modal -->
        <button id="viewAttendanceBtn" class="btn btn-success mb-3" data-toggle="modal" data-target="#attendanceModal">
            View Attendance Records
        </button>

        <!-- Modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendanceModalLabel">Attendance Records for <?= htmlspecialchars($employee_name); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $attendance_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['date']); ?></td>
                                        <td><?= htmlspecialchars($row['time_in']); ?></td>
                                        <td><?= htmlspecialchars($row['time_out']); ?></td>
                                        <td><?= htmlspecialchars($row['status']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($attendance_result): ?>
        <p>No attendance records found for this employee within the selected date range.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Check if attendance records exist
        <?php if ($attendance_result && $attendance_result->num_rows > 0): ?>
            $('#viewAttendanceBtn').on('click', function() {
                console.log("Opening attendance modal with records."); // Debug message
            });
        <?php else: ?>
            $('#viewAttendanceBtn').hide(); // Hide the button if no records
            console.log("No attendance records found."); // Debug message
        <?php endif; ?>
    });
</script>
</body>
</html>
