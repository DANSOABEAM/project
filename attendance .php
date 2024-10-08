<?php
// Backend: Handle the form submission and insert attendance into the database
$host = "localhost";
$username = "root";
$password = "";
$database = "mme_micro_credit";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the list of employees to display in the dropdown
$employees_result = $conn->query("SELECT id, first_name, last_name FROM employees");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $attendance_date = $_POST['date']; // Date chosen by the user
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $status = $_POST['status'];

    // Insert the attendance record
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, time_in, time_out, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $employee_id, $attendance_date, $time_in, $time_out, $status);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Attendance marked successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
 
<link rel="stylesheet" href="leave_apply.css">
<link rel="stylesheet" href="nav.css">
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
    <img src="Screenshot 2024-10-02 121004.png" width="20%"/>

        <h2 class="text-center">Mark Attendance</h2>
        <form method="POST" action=" ">
            <div class="form-group">
                <label for="employee_id">Employee Name:</label> <br> <br>
                <select class="form-control" id="employee_id" name="employee_id" required>
                    <option value="">Select Employee</option>
                    <?php
                    // Populate the dropdown with employee names
                    if ($employees_result->num_rows > 0) {
                        while ($employee = $employees_result->fetch_assoc()) {
                            echo "<option value='" . $employee['id'] . "'>" . htmlspecialchars($employee['first_name'] . " " . $employee['last_name']) . "</option>";
                            
                        }
                    } else {
                        echo "<option value=''>No Employees Found</option>";
                    }
                    ?>
 
                </select>
              

            </div>
            <br>
            <div class="form-group">
                <label for="attendance_date">Date:</label> <br>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="time_in">Time In:</label> <br>
                <input type="time" class="form-control" id="time_in" name="time_in" required>
            </div>
            <div class="form-group">
                <label for="time_out">Time Out:</label> <br>
                <input type="time" class="form-control" id="time_out" name="time_out" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label> <br>
                <select class="form-control" id="status" name="status" required>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Mark Attendance</button>
        </form>
    </div>
    <h5>&copy; Danso Francis Kwakye 2024</h5>
</body>
</html>
