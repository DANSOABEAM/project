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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $employee_id = $_POST['employee_id'];
    $request_date = $_POST['request_date'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Check for existing leave on the same date
    $check_sql = "SELECT * FROM leave_requests 
                  WHERE employee_id = '$employee_id' 
                  AND (start_date <= '$end_date' AND end_date >= '$start_date')";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<script>alert('This employee already has a leave request overlapping with the selected dates.');</script>";
    } else {
        // Insert into leave_requests table
        $sql = "INSERT INTO leave_requests (employee_id, leave_type,request_date, start_date, end_date, reason) 
                VALUES ('$employee_id', '$leave_type','$request_date', '$start_date', '$end_date', '$reason')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Leave request submitted successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    }
}

// Fetch all employees for the dropdown
$employees = $conn->query("SELECT id, first_name, last_name, profile_picture FROM employees");

// Leave types (can be customized)
$leave_types = ["Sick Leave", "Vacation", "Casual Leave", "Maternity Leave", "Paternity Leave"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            animation: fadeIn 1s;
        }
        .spinner {
            display: none;
            margin-top: 10px;
        }
        .employee-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: none;
            margin-top: 10px;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <script>
        function showEmployeeInfo() {
            var select = document.getElementById("employee_id");
            var selectedOption = select.options[select.selectedIndex];
            var image = selectedOption.getAttribute("data-image");
            var spinner = document.getElementById("spinner");
            var employeeInfo = document.getElementById("employee_info");
            var employeeImage = document.getElementById("employee_image");

            // Show spinner while loading the image
            spinner.style.display = "inline-block";
            employeeInfo.style.display = "none"; // Hide employee info while loading

            // Create a new image object to load the employee picture
            var img = new Image();
            img.src = image;

            img.onload = function() {
                employeeImage.src = image;
                spinner.style.display = "none"; // Hide spinner
                employeeImage.style.display = "block"; // Show employee image
                employeeInfo.style.display = "block"; // Show employee info
            };
            img.onerror = function() {
                spinner.style.display = "none"; // Hide spinner on error
                alert('Failed to load employee image.');
            };
        }
    </script>
    <style>
    h2{
        font-size:15px;
    }
    a{
        font-size:13px;
    }
    .btn{
        font-size:12px;
    }
    .form-control{
        margin-left:0px;
        margin-right:0px;
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
    <h2 class="text-center">Apply for Employee Leave</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="employee_id">Select Employee:</label>
            <select name="employee_id" id="employee_id" class="form-control" onchange="showEmployeeInfo()" required>
                <option value="">-- Select Employee --</option>
                <?php while ($row = $employees->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>" data-image="<?= htmlspecialchars($row['profile_picture']); ?>">
                        <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Employee Info -->
        <div id="employee_info" class="employee-info">
            <h5>Employee Information:</h5>
            <div class="spinner-border spinner" role="status" id="spinner">
                <span class="sr-only">Loading...</span>
            </div>
            <img id="employee_image" class="employee-image" src="" alt="Employee Image"> 
        </div>
        
        <hr>
        
        <div class="form-group">
            <label for="leave_type">Leave Type:</label>
            <select name="leave_type" class="form-control" required>
                <option value="">-- Select Leave Type --</option>
                <?php foreach ($leave_types as $type): ?>
                    <option value="<?= htmlspecialchars($type); ?>"><?= htmlspecialchars($type); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">request date:</label>
            <input type="date" name="request_date" class="form-control" required>
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
            <textarea name="reason" class="form-control" placeholder="Enter Reason" required></textarea>
        </div>

        <button type="submit" name="submit_leave" class="btn btn-primary">Submit Leave Request</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>
