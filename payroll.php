<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "mme_micro_credit"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_payroll'])) {
    $employee_id = $_POST['employee_id'];
    $salary = $_POST['salary'];
    $bonus = $_POST['bonus'];
    $deductions = $_POST['deductions'];
    $allowances = $_POST['allowances'];
    $pay_date = $_POST['pay_date']; 

    // Check for duplicate payroll entry
    $check_sql = "SELECT * FROM payroll WHERE employee_id = '$employee_id' AND pay_date = '$pay_date'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Error: Payroll entry already exists for this employee on the selected date.";
    } else {
        // Calculate total pay
        $total_pay = $salary + $bonus + $allowances - $deductions;

        // Insert into payroll table
        $sql = "INSERT INTO payroll (employee_id, salary, bonus, deductions, total_pay, pay_date, allowances) 
                VALUES ('$employee_id', '$salary', '$bonus', '$deductions', '$total_pay', '$pay_date', '$allowances')";

        if ($conn->query($sql) === TRUE) {
            echo "Payroll entry added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Retrieve employee list for dropdown
$employees = $conn->query("SELECT id, first_name, last_name, profile_picture FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <script>
        function showEmployeeInfo() {
            var loading = document.getElementById("loading");
            loading.style.display = "block"; // Show loading animation

            setTimeout(function() { // Simulate a delay for loading
                var select = document.getElementById("employee_id");
                var selectedOption = select.options[select.selectedIndex];
                var name = selectedOption.getAttribute("data-name");
                var image = selectedOption.getAttribute("data-image");

                document.getElementById("employee_name").textContent = name;
                document.getElementById("employee_image").src = image;
                document.getElementById("employee_info").style.display = "block";
                loading.style.display = "none"; // Hide loading animation
            }, 500); // Adjust the delay as needed
        }
    </script>
    <link rel="stylesheet" href="leave_apply.css">
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
        <h2 class="text-center">Payroll Management</h2>

        <!-- Payroll Entry Form -->
        <form method="POST" action="">
            <div class="form-group">
           
                <select name="employee_id" id="employee_id" class="form-control" onchange="showEmployeeInfo()" required>
                    <option value="">-- Select Employee --</option>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>" 
                                data-name="<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>" 
                                data-image="<?= htmlspecialchars($row['profile_picture']); ?>">
                            <?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Employee Info -->
            <div id="employee_info" class="employee-info">
                <h6>employee info:</h6>
                <img id="employee_image" class="employee-image" src="" alt="Employee Image">
                <p id="employee_name"></p>
            </div>

            <!-- Loading Animation -->
            <div id="loading" class="loading">
                <p>Loading...</p>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="form-group">
                <label for="salary">Salary:</label>
                <input type="number" name="salary" class="form-control" placeholder="Enter Salary" required>
            </div>
            <div class="form-group">
                <label for="bonus">Bonus:</label>
                <input type="number" name="bonus" class="form-control" placeholder="Enter Bonus" required>
            </div>
            <div class="form-group">
                <label for="deductions">Deductions:</label>
                <input type="number" name="deductions" class="form-control" placeholder="Enter Deductions" required>
            </div>
            <div class="form-group">
                <label for="allowances">Allowances:</label>
                <input type="number" name="allowances" class="form-control" placeholder="Enter Allowances" required>
            </div>

            <!-- Pay Date Input -->
            <div class="form-group">
                <label for="pay_date">Pay Date:</label>
                <input type="date" name="pay_date" class="form-control" required>
            </div>

            <button type="submit" name="submit_payroll" class="btn btn-primary">Submit Payroll</button>
        </form>
    </div>
   
</body>
</html>

<?php
$conn->close();
?>
