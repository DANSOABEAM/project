
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
// Fetch all employees from the database
$sql = "SELECT id, first_name, last_name, email, phone, department, hire_date, profile_picture, cv_document FROM employees";
$result = $conn->query($sql);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM employees WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Employee deleted successfully.";
        header("Location: view_employees.php"); // Redirect to the same page to refresh the list
        exit();
    } else {
        echo "Error deleting employee: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Employees</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            font-size:13px;

        }
a{
    
}
        td {
            padding: 10px;
            text-align: left;
           
        }
                    th{
                        background-color: green;
                        color:white;
                    }
        img {
            width: 50px;
            height: 50px;
        }

        .btn {
            padding: 5px 10px;
            margin: 2px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }

        .edit-btn {
            background-color: #007bff; /* Bootstrap primary color */
        }

        .delete-btn {
            background-color: #dc3545; /* Bootstrap danger color */
        }

        body{
   

}
th{
    text-align:centre;

    padding: 10px;
}


ul{
   background:transparent;
    display: flex;
   padding: 10px;
  
   box-shadow: 0 0 10px black;
  
}

li{
    margin: auto;
    padding: 15px;
    font-size: 16px;
    list-style: none;
}

a{
   
    text-decoration: none;

}

li :hover{
  
    color: green;
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



    <h2>Employee List</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Profile Picture</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Hire Date</th>
            <th>CV Document</th>
            <th>Actions</th> <!-- New actions column -->
        </tr>

        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td><img src='" . htmlspecialchars($row["profile_picture"]) . "' alt='Profile Picture'></td>";
                echo "<td>" . htmlspecialchars($row["first_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["last_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["department"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["hire_date"]) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row["cv_document"]) . "' download>Download CV</a></td>";
                echo "<td>";
                echo "<a href='edit_employee.php?id=" . $row["id"] . "' class='btn edit-btn'>Edit</a>";
                echo "<a href='?delete_id=" . $row["id"] . "' class='btn delete-btn' onclick=\"return confirm('Are you sure you want to delete this employee?');\">Remove</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No employees found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
   
</body>
</html>
