<?php
// Start session to check if the user is logged in
session_start();
$servername = "localhost";
$username = "root";
$password = "";  // Your database password
$dbname = "mme_micro_credit";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle leave request submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $employee_id = $_SESSION['employee_id']; // Employee ID from session
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Insert leave request into the database
    $sql = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason, status) 
            VALUES ('$employee_id', '$leave_type', '$start_date', '$end_date', '$reason', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Leave request submitted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle leave approval/rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['approve']) || isset($_POST['reject']))) {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    $leave_id = $_POST['leave_id'];
    
    if (isset($_POST['approve'])) {
        $sql = "UPDATE leave_requests SET status='Approved' WHERE id='$leave_id'";
        $conn->query($sql);
    } elseif (isset($_POST['reject'])) {
        $sql = "UPDATE leave_requests SET status='Rejected' WHERE id='$leave_id'";
        $conn->query($sql);
    }
    
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
    exit();
}

// Fetch leave requests for admin
$leave_requests = [];
if (isset($_SESSION['admin_id'])) {
    $sql = "SELECT lr.*, e.first_name, e.last_name FROM leave_requests lr 
            JOIN employees e ON lr.employee_id = e.id";
    $leave_requests = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <link rel="stylesheet" href="leave.css">
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

        <?php if (isset($_SESSION['employee_id'])): ?>
            <!-- Leave Request Form for Employees -->
            <h4>Request Leave</h4>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="leave_type">Leave Type:</label>
                    <select name="leave_type" class="form-control" required>
                        <option value="">-- Select Leave Type --</option>
                        <option value="sick">Sick Leave</option>
                        <option value="vacation">Vacation Leave</option>
                        <option value="personal">Personal Leave</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="reason">Reason:</label>
                    <textarea name="reason" class="form-control" required></textarea>
                </div>
                <button type="submit" name="submit_leave" class="btn btn-primary">Submit Leave Request</button>
            </form>
        <?php endif; ?>

        <?php if (isset($_SESSION['admin_id'])): ?>
            <!-- Leave Requests Table for Admins -->
            <h4>Leave Requests</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($leave_requests->num_rows > 0): ?>
                        <?php while($row = $leave_requests->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['leave_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="approve" class="btn btn-success">Approve</button>
                                        <button type="submit" name="reject" class="btn2 btn-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No leave requests found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
  
</body>
</html>

<?php
$conn->close();
?>
