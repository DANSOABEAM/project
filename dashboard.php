<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "mme_micro_credit"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for the dashboard
$employee_count_result = $conn->query("SELECT COUNT(*) AS count FROM employees");
$employee_count = $employee_count_result->fetch_assoc()['count'];

$pending_leave_count_result = $conn->query("SELECT COUNT(*) AS count FROM leave_requests WHERE status = 'pending'");
$pending_leave_count = $pending_leave_count_result->fetch_assoc()['count'];

// Add any other data fetching queries as needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            background-image: linear-gradient(rgba(0, 0, 0, 0.382),rgba(0, 0, 0, 0.974)),url(WhatsApp\ Image\ 2024-09-26\ at\ 13.09.52_c57ebbf2.jpg);

            background-repeat:no-repeat;
            background-size:cover;

        }

        .container {
            width: 90%;
            margin: auto;
            overflow: hidden;
            animation: fadeIn 1.5s ease-in-out;
        }

        h1 {
            text-align: center;
            margin-top: 25px;
            font-size:25px;
            color: #ffffff;
            animation: bounceIn 1.5s;
        }
h2{
    font-size:15px;
}
        .dashboard {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .card {
            background-color: #007bff;
            color: white;
            border-radius: 10px;
         
            width: 30%;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: zoomIn 1s;
           
        }

        .card:hover {
            transform: scale(1.1);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card i {
            font-size: 40px;
            margin-top: 10px;
        }

        .card p {
            font-size: 2.2rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .links {
            margin-top:-10%;
            margin: 10px 0;
            text-align: center;
            animation: fadeInUp 2s;
        }

        .links a {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 5px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 15px;
            transition: background-color 0.3s, transform 0.3s;
            animation: slideIn 1s;
        }

        .links a:hover {
            background-color: #218838;
            transform: translateY(-5px);
        }

        .links i {
            margin-left: 10px;
        }
      
        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
            }
            to {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="dashboard">
            <!-- Data Cards -->
            <div class="card">
                <h2>Employees   <i class="fas fa-users"></i></h2>
                <p><?= $employee_count; ?></p>
               
            </div>
            <div class="card">
                <h2>Pending Leave Requests   <i class="fas fa-calendar-alt"></i></h2>
                <p><?= $pending_leave_count; ?></p>
              
            </div>

            <!-- Add more cards as needed -->
        </div>

        <!-- Navigation Links to Important Pages -->
        <div class="links">
            <a href="leave_apply.php">Leave Application <i class="fas fa-users"></i></a><br>
            <a href="add_employee.php">Add Employee <i class="fas fa-calendar-alt"></i></a><br>
            <a href="attendance .php">Attendance <i class="fas fa-money-check-alt"></i></a><br>
            <a href="view_emplyee.php">View Employees <i class="fas fa-clock"></i></a><br>
            <a href="reporting.php">View Reports <i class="fas fa-chart-line"></i></a><br>
            <a href="leave.php">Leave Management <i class="fas fa-chart-line"></i></a><br>
            <a href="payroll.php">Payroll <i class="fas fa-chart-line"></i></a><br>
            <a href="employee_payroll.php">Employee Payroll <i class="fas fa-chart-line"></i></a><br>
            <a href="employee_attendance.php">employee attendance record <i class="fas fa-chart-line"></i></a><br>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
