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
        $sql = "INSERT INTO leave_requests (employee_id, leave_type, start_date, end_date, reason) 
                VALUES ('$employee_id', '$leave_type', '$start_date', '$end_date', '$reason')";

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
    <link rel="stylesheet" href="leave_apply.css">
    <link rel="stylesheet" href="nav.css">
   
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
                employeeInfo.style.display = "block"; // Show employee info
            };
            img.onerror = function() {
                spinner.style.display = "none"; // Hide spinner on error
                alert('Failed to load employee image.');
            };
        }
    </script>
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
<div class="form-group">

        <form method="POST" action="">
         
            <h2 class="text-center">Apply for Employee Leave</h2>
          
                <select name="employee_id" id="employee_id" class="form-control" onchange="showEmployeeInfo()" required>
                    <option value="">-- Select Employee --</option>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>" data-image="<?= htmlspecialchars($row['profile_picture']); ?>">
                            <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
         

            <!-- Employee Info -->
            <div id="employee_info" class="employee-info">
                <h5>Employee Information:</h5>
                <div class="spinner" id="spinner"></div>
                <img id="employee_image" class="employee-image" src="" alt="Employee Image"> <br>
           
                    </div>
<HR></HR>
                <div class="one">
          
                <label for="leave_type">Leave Type:</label><br> <br>
                <select name="leave_type" class="form-control" required><br>
                    <option value="">-- Select Leave Type --</option>
                    <?php foreach ($leave_types as $type): ?>
                        <option value="<?= htmlspecialchars($type); ?>"><?= htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>
           <br>
                <label for="start_date">Start Date:</label><br>
                <input type="date" name="start_date" class="form-control" required><br>
        
         
                <label for="end_date">End Date:</label><br>
                <input type="date" name="end_date" class="form-control" required><br>
         
        
                <label for="reason">Reason:</label><br>
                <textarea name="reason" class="form-control" placeholder="Enter Reason" required></textarea><br>
                <button type="submit" name="submit_leave" class="btn btn-primary">Submit Leave Request</button>
                </form>
                </div>
                    </div>


               
           
        </form>
                  
 
    
</body>
</html>

<?php
$conn->close();
?>
