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
        header("Location: view_employees.php"); // Redirect to the same page to refresh the list
        exit();
    } else {
        echo "Error deleting employee: " . $conn->error;
    }
}

// Handle edit request
$edit_employee = null;
if (isset($_POST['edit_id'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_sql = "SELECT * FROM employees WHERE id = $edit_id";
    $edit_employee = $conn->query($edit_sql)->fetch_assoc();
}

// Update employee details
if (isset($_POST['update_employee'])) {
    $id = intval($_POST['id']);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];

    $update_sql = "UPDATE employees SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', department='$department' WHERE id=$id";
    
    if ($conn->query($update_sql) === TRUE) {
        header("Location: view_employees.php"); // Redirect to refresh the list
        exit();
    } else {
        echo "Error updating employee: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Employees</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            font-size: 13px;
        }

        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: green;
            color: white;
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

        ul {
            background: transparent;
            display: flex;
            padding: 10px;
            background-color: red;
            box-shadow: 0 0 5px rgba(128, 0, 128, 0.532);
        }

        li {
            margin: auto;
            padding: 15px;
            font-size: 16px;
            list-style: none;
        }

        a {
            color: white;
            text-decoration: none;
        }

        li:hover {
            color: green;
        }
    </style>
</head>
<body>

<ul>
    <li><a href="leave_apply.php">Leave Application</a></li>
    <li><a href="add_employee.php">Add Employee</a></li>
    <li><a href="attendance.php">Attendance</a></li>
    <li><a href="view_employees.php">All Employees</a></li>
    <li><a href="reporting.php">Reports</a></li>
    <li><a href="leave.php">Leave Management</a></li>
    <li><a href="payroll.php">Payroll</a></li>
    <li><a href="employee_payroll.php">Employee Payroll</a></li>
    <li><a href="employee_attendance.php">Employee Attendance Record</a></li>
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
        <th>Actions</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
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
            echo "<a href='#' class='btn edit-btn' data-toggle='modal' data-target='#editModal' data-id='" . $row["id"] . "' data-firstname='" . htmlspecialchars($row["first_name"]) . "' data-lastname='" . htmlspecialchars($row["last_name"]) . "' data-email='" . htmlspecialchars($row["email"]) . "' data-phone='" . htmlspecialchars($row["phone"]) . "' data-department='" . htmlspecialchars($row["department"]) . "'>Edit</a>";
            echo "<a href='?delete_id=" . $row["id"] . "' class='btn delete-btn' onclick=\"return confirm('Are you sure you want to delete this employee?');\">Remove</a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No employees found</td></tr>";
    }
    ?>
</table>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" name="department" id="department" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_employee" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Populate modal with employee data
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var firstName = button.data('firstname');
        var lastName = button.data('lastname');
        var email = button.data('email');
        var phone = button.data('phone');
        var department = button.data('department');

        // Update the modal's content
        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#first_name').val(firstName);
        modal.find('#last_name').val(lastName);
        modal.find('#email').val(email);
        modal.find('#phone').val(phone);
        modal.find('#department').val(department);
    });
</script>

</body>
</html>
