<?php
// Database connection
$servername = "localhost";
$username = "root"; // 
$password = ""; 
$dbname = "mme_micro_credit"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to fetch payroll
$employee = null;
$payroll_result = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_payroll'])) {
    $employee_id = $_POST['employee_id'];

    // Fetch employee details including profile picture
    $employee_sql = "SELECT first_name, last_name, profile_picture FROM employees WHERE id = '$employee_id'";
    $employee_result = $conn->query($employee_sql);
    $employee = $employee_result->fetch_assoc();

    // Fetch payroll records
    $payroll_sql = "SELECT * FROM payroll WHERE employee_id = '$employee_id'";
    $payroll_result = $conn->query($payroll_sql);
}

// Fetch all employees for the dropdown
$employees = $conn->query("SELECT id, first_name, last_name FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payroll Print</title>
    <link rel="stylesheet" href="employee_payroll.css">
    <link rel="stylesheet" href="nav.css">
</head>
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













<body>
    <div class="container">
    <div class="form-group">
        <h2 class="text-center">Print Employee Payroll</h2>

        <!-- Form to select employee -->
        <form method="POST" action="">
          
                 
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">-- Select Employee --</option>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>">
                            <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <br>
                <button type="submit" name="submit_payroll" class="btn btn-primary">process</button>
            </div>
                       
           
        </form>
        

        <?php if ($payroll_result && $payroll_result->num_rows > 0): ?>
            <div class="employee-details">
           
             
                <img src="<?= htmlspecialchars($employee['profile_picture']); ?>" alt="Profile Picture" width="100">
                <h4>Payroll for <?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h4>
            </div>
            </div>
        </div>
            <table class="table table-bordered payroll-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Salary</th>
                        <th>Bonus</th>
                        <th>Deductions</th>
                        <th>Total Pay</th>
                        <th>Pay Date</th>
                        <th>Allowances</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $payroll_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= number_format($row['salary'], 2); ?></td>
                            <td><?= number_format($row['bonus'], 2); ?></td>
                            <td><?= number_format($row['deductions'], 2); ?></td>
                            <td><?= number_format($row['total_pay'], 2); ?></td>
                            <td><?= date("Y-m-d", strtotime($row['pay_date'])); ?></td>
                            <td><?= number_format($row['allowances'], 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <button class="btn1 btn-success print-button" onclick="window.print()">Print Payroll</button>
        <?php elseif ($payroll_result && $payroll_result->num_rows == 0): ?>
            <h4>No payroll records found for this employee.</h4>
        <?php endif; ?>
  
 
</body>
</html>

<?php
$conn->close();
?>
