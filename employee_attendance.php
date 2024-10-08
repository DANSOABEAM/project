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

// Initialize variables for attendance query
$attendance_result = null;
$employee_name = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch employee name
    $employee_sql = "SELECT first_name, last_name FROM employees WHERE id = '$employee_id'";
    $employee_result = $conn->query($employee_sql);
    $employee_data = $employee_result->fetch_assoc();
    $employee_name = $employee_data['first_name'] . ' ' . $employee_data['last_name'];

    // Fetch attendance records for the selected employee within the date range
    $attendance_sql = "SELECT * FROM attendance WHERE employee_id = '$employee_id' 
                       AND date BETWEEN '$start_date' AND '$end_date'";
    $attendance_result = $conn->query($attendance_sql);
}

?>

<<!DOCTYPE html>
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
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        @media print {
            #printBtn {
                display: none; /* Hide print button when printing */
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">MME Micro Credit</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
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

<div class="container">
    <h2>Employee Attendance Report</h2>
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
        <button id="printBtn" class="btn btn-success mb-3">Print Attendance Records</button>
        <h3>Attendance Records for <?= htmlspecialchars($employee_name); ?></h3>
        <table class="table table-bordered" id="attendanceTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $attendance_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['date']); ?></td>
                        <td><?= htmlspecialchars($row['time_in']); ?></td>
                        <td><?= htmlspecialchars($row['time_out']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif ($attendance_result): ?>
        <p>No attendance records found for this employee within the selected date range.</p>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Records for <?= htmlspecialchars($employee_name); ?></h5>
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
                        </tr>
                    </thead>
                    <tbody id="modalBody">
                        <?php if ($attendance_result && $attendance_result->num_rows > 0): ?>
                            <?php while ($row = $attendance_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['date']); ?></td>
                                    <td><?= htmlspecialchars($row['time_in']); ?></td>
                                    <td><?= htmlspecialchars($row['time_out']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="printModalBtn" class="btn btn-primary">Print</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Show modal on print button click
    document.getElementById("printBtn").onclick = function() {
        $('#myModal').modal('show');
    }

    // Print modal content
    document.getElementById("printModalBtn").onclick = function() {
        var printContent = document.getElementById("modalBody").innerHTML;
        var newWindow = window.open('', '', 'width=600,height=400');
        newWindow.document.write('<html><head><title>Print Attendance</title>');
        newWindow.document.write('</head><body>');
        newWindow.document.write('<h3>Attendance Records for <?= htmlspecialchars($employee_name); ?></h3>');
        newWindow.document.write('<table border="1" cellpadding="10"><thead><tr><th>Date</th><th>Time In</th><th>Time Out</th></tr></thead><tbody>');
        newWindow.document.write(printContent);
        newWindow.document.write('</tbody></table>');
        newWindow.document.write('</body></html>');
        newWindow.document.close();
        newWindow.print();
    }
</script>
</body>
</html>

</body>
</html>
