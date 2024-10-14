<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'mme_micro_credit');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$report_data = [];
$report_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];


    switch ($report_type) {
        case 'attendance':
            $report_sql = "SELECT * FROM attendance WHERE date BETWEEN '$start_date' AND '$end_date'";
            break;

        case 'payroll':
            $report_sql = "SELECT * FROM payroll WHERE pay_date BETWEEN '$start_date' AND '$end_date'";
            break;

        case 'leave':
            $report_sql = "SELECT * FROM leave_requests WHERE request_date BETWEEN '$start_date' AND '$end_date'";
            break;

        default:
            die("Invalid report type.");
    }

    $report_result = $conn->query($report_sql);

    // Check for errors
    if (!$report_result) {
        $report_error = "Error fetching report records: " . $conn->error;
    } else {
        while ($row = $report_result->fetch_assoc()) {
            $report_data[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Report Generation</title>
   <style>
    h2{
        font-size:17px;
            text-align:centre;
            margin-left:40%;
    }

    .btn{
        font-size:12px;
            
    }
    hr{
        width:40%;
        color:black;
        height:5vh;
    }
    table{
        width:100%
    }
   </style>
</head>
<body>

<div class="container mt-5">
    <h2>Generate Report</h2>
    <hr>
    <form method="POST" action="">
        <div class="form-group">
            <label for="report_type">Select Report Type:</label>
            <select class="form-control" id="report_type" name="report_type" required>
                <option value="attendance">Attendance</option>
                <option value="payroll">Payroll</option>
                <option value="leave">Leave Requests</option>
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Report</button>
    </form>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-info mt-3" data-toggle="modal" data-target="#reportModal">
        View Report
    </button>

    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($report_error): ?>
                        <div class="alert alert-danger"><?php echo $report_error; ?></div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php if ($report_type === 'attendance'): ?>
                                        <th>ID</th>
                                        <th>Employee ID</th>
                                        <th>Attendance Date</th>
                                    <?php elseif ($report_type === 'payroll'): ?>
                                        <th>ID</th>
                                        <th>Employee ID</th>
                                        <th>Pay Date</th>
                                        <th>Amount</th>
                                    <?php elseif ($report_type === 'leave'): ?>
                                        <th>ID</th>
                                        <th>Employee ID</th>
                                        <th>request date</th>
                                        <th>Leave Start</th>
                                        <th>Leave End</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($report_data as $row): ?>
                                    <tr>
                                        <?php if ($report_type === 'attendance'): ?>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['employee_id']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                        <?php elseif ($report_type === 'payroll'): ?>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['employee_id']; ?></td>
                                            <td><?php echo $row['pay_date']; ?></td>
                                            <td><?php echo $row['total_pay']; ?></td>
                                        <?php elseif ($report_type === 'leave'): ?>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['employee_id']; ?></td>
                                            <td><?php echo $row['request_date']; ?></td>
                                            <td><?php echo $row['start_date']; ?></td>
                                            <td><?php echo $row['end_date']; ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printReport()">Print Report</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printReport() {
    const printContent = document.querySelector('.modal-body').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>Print Report</title></head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


SSJVOEFOWEFOFSDFNJNJNJNFSAJSDNNDS