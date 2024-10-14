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

    // Check if the employee already has attendance marked for that date
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM attendance WHERE employee_id = ? AND date = ?");
    $check_stmt->bind_param("is", $employee_id, $attendance_date);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        echo "<div class='alert alert-danger text-center'>Attendance for this employee on this date has already been marked!</div>";
    } else {
        // Insert the attendance record
        $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, time_in, time_out, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $employee_id, $attendance_date, $time_in, $time_out, $status);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success text-center'>Attendance marked successfully!</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #343a40;
        }

        /* Navbar Styles */
        nav {
            background-color: #343a40;
            padding: 10px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-toggle {
            display: none;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .navbar-menu {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .navbar-menu li {
            margin: 0 10px;
        }

        .navbar-menu li a {
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navbar-menu li a:hover {
            background-color: #495057;
        }

        /* Container Styles */
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Footer Styles */
        h5 {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }

        /* Alert Styles */
        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
                flex-direction: column;
                width: 100%;
            }

            .navbar-menu.active {
                display: flex;
            }

            .navbar-toggle {
                display: block;
            }

            .navbar-menu li {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="navbar">
        <button class="navbar-toggle">Menu</button>
        <ul class="navbar-menu">
            <li><a href="leave_apply.php">Leave Application</a></li>
            <li><a href="add_employee.php">Add Employee</a></li>
            <li><a href="attendance.php">Attendance</a></li>
            <li><a href="view_employee.php">All Employees</a></li>
            <li><a href="reporting.php">Reports</a></li>
            <li><a href="leave.php">Leave Management</a></li>
            <li><a href="payroll.php">Payroll</a></li>
            <li><a href="employee_payroll.php">Employee Payroll</a></li>
            <li><a href="employee_attendance.php">Employee Attendance Record</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h2>Mark Attendance</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="employee_id">Employee Name:</label>
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
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="time_in">Time In:</label>
            <input type="time" class="form-control" id="time_in" name="time_in" >
        </div>
        <div class="form-group">
            <label for="time_out">Time Out:</label>
            <input type="time" class="form-control" id="time_out" name="time_out" >
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>
        </div>
        <button type="submit" class="btn">Mark Attendance</button>
    </form>
</div>

<h5>&copy; Danso Francis Kwakye 2024</h5>

<script>
    // Script to toggle the navbar on small screens
    const toggleButton = document.querySelector('.navbar-toggle');
    const navbarMenu = document.querySelector('.navbar-menu');

    toggleButton.addEventListener('click', () => {
        navbarMenu.classList.toggle('active');
    });
</script>
</body>
</html>
