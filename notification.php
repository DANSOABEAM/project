<?php
// Include Composer's autoloader
require 'autoload.php';
use Twilio\Rest\Client;

// Database connection
$servername = "localhost";
$username = "root"; // Change to your DB username
$password = ""; // Change to your DB password
$dbname = "mme_micro_credit"; // Change to your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Twilio credentials
$sid = 'YOUR_TWILIO_SID'; // Your Twilio SID
$token = 'YOUR_TWILIO_TOKEN'; // Your Twilio Token
$twilio_number = 'YOUR_TWILIO_NUMBER'; // Your Twilio number

// Function to send SMS
function sendSMS($to, $message) {
    global $sid, $token, $twilio_number;

    $client = new Client($sid, $token);
    $client->messages->create($to, [
        'from' => $twilio_number,
        'body' => $message
    ]);
}

// Handle leave request approval
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
    $leave_id = $_POST['leave_id'];
    $employee_id = $_POST['employee_id'];
    
    // Update leave status in the database
    $update_sql = "UPDATE leave_requests SET status = 'Approved' WHERE id = '$leave_id'";
    if ($conn->query($update_sql) === TRUE) {
        // Fetch employee phone number
        $employee_sql = "SELECT phone FROM employees WHERE id = '$employee_id'";
        $employee_result = $conn->query($employee_sql);
        $employee = $employee_result->fetch_assoc();

        // Send SMS notification
        sendSMS($employee['phone'], "Your leave request has been approved.");
        echo "Leave approved and notification sent!";
    }
}

// Handle leave request rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reject'])) {
    $leave_id = $_POST['leave_id'];
    $employee_id = $_POST['employee_id'];
    
    // Update leave status in the database
    $update_sql = "UPDATE leave_requests SET status = 'Rejected' WHERE id = '$leave_id'";
    if ($conn->query($update_sql) === TRUE) {
        // Fetch employee phone number
        $employee_sql = "SELECT phone FROM employees WHERE id = '$employee_id'";
        $employee_result = $conn->query($employee_sql);
        $employee = $employee_result->fetch_assoc();

        // Send SMS notification
        sendSMS($employee['phone'], "Your leave request has been rejected.");
        echo "Leave rejected and notification sent!";
    }
}

// Fetch leave requests for the admin to approve or reject
$leave_requests = $conn->query("SELECT * FROM leave_requests WHERE status = 'Pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h2>Leave Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee ID</th>
                    <th>Leave Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $leave_requests->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['employee_id']; ?></td>
                    <td><?= $row['leave_type']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="employee_id" value="<?= $row['employee_id']; ?>">
                            <button type="submit" name="approve">Approve</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="leave_id" value="<?= $row['id']; ?>">
                            <input type="hidden" name="employee_id" value="<?= $row['employee_id']; ?>">
                            <button type="submit" name="reject">Reject</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
