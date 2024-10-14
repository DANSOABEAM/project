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
        echo "<div class='alert alert-danger'>Error: Payroll entry already exists for this employee on the selected date.</div>";
    } else {
        // Calculate total pay
        $total_pay = $salary + $bonus + $allowances - $deductions;

        // Insert into payroll table
        $sql = "INSERT INTO payroll (employee_id, salary, bonus, deductions, total_pay, pay_date, allowances) 
                VALUES ('$employee_id', '$salary', '$bonus', '$deductions', '$total_pay', '$pay_date', '$allowances')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Payroll entry added successfully.</div>";
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
    <title>Payroll Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-control {
            background-color: #f1f1f1;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            margin-top: 20px;
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .employee-info {
            display: none;
            margin-top: 20px;
            text-align: center;
            padding: 10px;
            background-color: #f1f9ff;
            border: 2px dashed #007bff;
            border-radius: 10px;
            animation: slideIn 0.5s ease;
        }
        a{
            font-size:10px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        #employee_image {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        #loading {
            display: none;
            text-align: center;
        }

        .navbar {
            background-color: #343a40;
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            padding: 8px 15px;
            border-radius: 50px;
            transition: background-color 0.3s ease-in-out;
        }

        .navbar a:hover {
            background-color: #495057;
        }

        .navbar a.active {
            background-color: #007bff;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <a href="leave_apply.php">Leave Application</a>
        <a href="add_employee.php">Add Employee</a>
        <a href="attendance.php">Attendance</a>
        <a href="view_emplyee.php">All Employees</a>
        <a href="reporting.php">Reports</a>
        <a href="leave.php">Leave Management</a>
        <a href="payroll.php" class="active">Payroll</a>
        <a href="employee_payroll.php">Employee Payroll</a>
        <a href="employee_attendance.php">Attendance Record</a>
    </nav>

    <div class="container">
        <h2>Payroll Management</h2>

        <!-- Payroll Form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="employee_id">Select Employee</label>
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
                <h6>Employee Info</h6>
                <img id="employee_image" src="" alt="Employee Image">
                <p id="employee_name"></p>
            </div>

            <!-- Loading Animation -->
            <div id="loading" class="loading">
                <p>Loading...</p>
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <!-- Payroll Form Fields -->
            <div class="mb-3">
                <label for="salary">Salary</label>
                <input type="number" name="salary" class="form-control" placeholder="Enter Salary" required>
            </div>

            <div class="mb-3">
                <label for="bonus">Bonus</label>
                <input type="number" name="bonus" class="form-control" placeholder="Enter Bonus" required>
            </div>

            <div class="mb-3">
                <label for="deductions">Deductions</label>
                <input type="number" name="deductions" class="form-control" placeholder="Enter Deductions" required>
            </div>

            <div class="mb-3">
                <label for="allowances">Allowances</label>
                <input type="number" name="allowances" class="form-control" placeholder="Enter Allowances" required>
            </div>

            <div class="mb-3">
                <label for="pay_date">Pay Date</label>
                <input type="date" name="pay_date" class="form-control" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="submit_payroll" class="btn btn-primary">Submit Payroll</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        function showEmployeeInfo() {
            var loading = document.getElementById("loading");
            loading.style.display = "block";

            setTimeout(function() {
                var select = document.getElementById("employee_id");
                var selectedOption = select.options[select.selectedIndex];
                var name = selectedOption.getAttribute("data-name");
                var image = selectedOption.getAttribute("data-image");

                document.getElementById("employee_name").textContent = name;
                document.getElementById("employee_image").src = image;
                document.getElementById("employee_info").style.display = "block";
                loading.style.display = "none";
            }, 500);
        }
    </script>
</body>
</html>


<?php
$conn->close();
?>
