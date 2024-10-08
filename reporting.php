<?php
// Start session to check if admin is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize variables
$report_data = [];
$message = '';

// Database connection
$servername = "localhost";
$username = "root"; // Update as necessary
$password = "";     // Update as necessary
$dbname = "mme_micro_credit"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle report generation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Query the database based on report type
    if ($report_type) {
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

    // Prepare the message if no results
    if ($report_data && $report_data->num_rows === 0) {
        $message = "No records found for the selected criteria.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 30px;
            border-radius: 8px;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
        .text-danger {
            margin-bottom: 15px;
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
                <li class="nav-item"><a class="nav-link" href="view_emplyee.php">All Employees</a></li>
                <li class="nav-item"><a class="nav-link active" href="reporting.php">Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="leave.php">Leave Management</a></li>
                <li class="nav-item"><a class="nav-link" href="payroll.php">Payroll</a></li>
                <li class="nav-item"><a class="nav-link" href="employee_payroll.php">Employee Payroll</a></li>
                <li class="nav-item"><a class="nav-link" href="employee_attendance.php">Employee Attendance Record</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center">Generate Reports</h2>
        <form id="reportForm" method="POST">
            <div class="form-group">
                <label for="report_type">Report Type:</label>
                <select name="report_type" id="report_type" class="form-control" required>
                    <option value="attendance">Attendance Report</option>
                    <option value="payroll">Payroll Report</option>
                    <option value="leave">Leave Report</option>
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
            <button type="button" class="btn btn-primary" id="generateReportBtn">Generate Report</button>
        </form>

        <!-- Modal for displaying report -->
        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModalLabel">Generated Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($message)) : ?>
                            <p class="text-danger"><?php echo $message; ?></p>
                        <?php else: ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <?php if (isset($report_type) && $report_type == 'attendance'): ?>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        <?php elseif (isset($report_type) && $report_type == 'payroll'): ?>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Total Pay</th>
                                            <th>Pay Date</th>
                                        <?php elseif (isset($report_type) && $report_type == 'leave'): ?>
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
                                    <?php if (isset($report_data) && $report_data && $report_data->num_rows > 0): ?>
                                        <?php while ($row = $report_data->fetch_assoc()): ?>
                                            <tr>
                                                <?php if (isset($report_type) && $report_type == 'attendance'): ?>
                                                    <td><?php echo $row['first_name']; ?></td>
                                                    <td><?php echo $row['last_name']; ?></td>
                                                    <td><?php echo $row['date']; ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                <?php elseif (isset($report_type) && $report_type == 'payroll'): ?>
                                                    <td><?php echo $row['first_name']; ?></td>
                                                    <td><?php echo $row['last_name']; ?></td>
                                                    <td><?php echo $row['total_pay']; ?></td>
                                                    <td><?php echo $row['pay_date']; ?></td>
                                                <?php elseif (isset($report_type) && $report_type == 'leave'): ?>
                                                    <td><?php echo $row['first_name']; ?></td>
                                                    <td><?php echo $row['last_name']; ?></td>
                                                    <td><?php echo $row['leave_type']; ?></td>
                                                    <td><?php echo $row['start_date']; ?></td>
                                                    <td><?php echo $row['end_date']; ?></td>
                                                    <td><?php echo $row['status']; ?></td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#generateReportBtn').on('click', function () {
                $('#reportModal').modal('show');
                $('#reportForm').submit();
            });
        });
    </script>
</body>
</html>
