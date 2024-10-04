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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Report</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color:black;
          
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
         
            border-radius: 8px;
           
        }

        h2 {
            margin-left: 3%;
            font-size: 24px;
            margin-bottom: 20px;
           
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
         
            display: block;
            margin-bottom: 5px;
            margin-left: 3%;
        }

        select, input[type="date"], button {
          
          
    width: 50%;
    height: 5vh;
    margin-left: 15%;
    border-left: 0;
    border-right: 0;
    border-top: 0;
    margin: 15px;
    border-bottom: 1px solid rgba(14, 24, 16, 0.93);
   background-color: rgba(255, 255, 255, 0.143);
        }

        input{
            background:transparent;
            width:20%;
        }

        button {
            background-color: #28a745;
          
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #dddddd;
        }

        th, td {
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #007bff;
           
        }

        select{
            color:black;
        }


        ul{
    background-color: transparent;
    display: flex;
   padding: 10px;
   
  box-shadow: 0 0 60px black;
 
  
}

li{
    margin: auto;
    padding: 10px;
    font-size: 14px;
    list-style: none;
}

a{
    
    text-decoration: none;

}

li :hover{
 
   
}
    </style>
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
    <h2>Employee Attendance Report</h2>

    <form method="POST" action="" class="fade-in">
        <div class="form-group">
            <label for="employee_id">Select Employee:</label>
            <select name="employee_id" id="employee_id" required>
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
            <input type="date" name="start_date" id="start_date" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" id="end_date" required>
        </div>

        <button type="submit">View Attendance</button>
    </form>

    <?php if ($attendance_result && $attendance_result->num_rows > 0): ?>
        <h3>Attendance Records for <?= htmlspecialchars($employee_name); ?></h3>
        <table class="fade-in">
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

</body>
</html>

<?php
// Close connection
$conn->close();
?>
