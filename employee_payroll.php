<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "your_database_name");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Fetch payroll data for the selected employee and date range
    $query = "SELECT * FROM payroll WHERE employee_id = '$employee_id' AND payroll_date BETWEEN '$start_date' AND '$end_date'";
    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payroll Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Employee Payroll Report</h2>

        <!-- Form to select employee and date range -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="employee_id">Select Employee:</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <?php
                    // Fetch all employees from the database
                    $employee_query = "SELECT * FROM employees";
                    $employee_result = $conn->query($employee_query);
                    while ($employee = $employee_result->fetch_assoc()) {
                        echo "<option value='" . $employee['employee_id'] . "'>" . $employee['employee_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>

        <!-- Payroll report modal -->
        <?php if (isset($result) && $result->num_rows > 0): ?>
        <div class="modal" id="payrollModal" style="display:block; background: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payroll Report</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('payrollModal').style.display='none';">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Payroll ID</th>
                                    <th>Employee ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['payroll_id']; ?></td>
                                    <td><?php echo $row['employee_id']; ?></td>
                                    <td><?php echo $row['payroll_date']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button onclick="printPayroll()" class="btn btn-success">Print</button>
                    </div>
                </div>
            </div>
        </div>
        <?php elseif (isset($result)): ?>
            <p>No payroll records found for this employee within the selected date range.</p>
        <?php endif; ?>
    </div>

    <script>
    function printPayroll() {
        var printContents = document.querySelector('.modal-body').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
