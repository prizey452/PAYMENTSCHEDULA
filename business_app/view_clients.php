<?php
include('config/db.php');

// Fetch all clients from the database
$sql = "SELECT * FROM Clients";
$result = $conn->query($sql);

// Reminder Logic
$today = date('Y-m-d');
$reminders = []; // Array to store reminders
$errors = [];

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['expiry_date'] <= $today) {
                $reminders[] = [
                    'name' => $row['name'],
                    'phone' => $row['phone_number'],
                    'expiry' => $row['expiry_date'],
                    'type' => 'expired'
                ];
            } elseif (strtotime($row['expiry_date']) - strtotime($today) <= (2 * 86400)) { // 2 days in seconds
                $reminders[] = [
                    'name' => $row['name'],
                    'phone' => $row['phone_number'],
                    'expiry' => $row['expiry_date'],
                    'type' => 'expiring'
                ];
            }
        }
    }
} else {
    $errors[] = "Error fetching clients: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client List</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <a href="add_client.php" class="btn add-client-btn">Add New Client</a>  <h1>Client List</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Errors:</strong><br>
            <?php foreach ($errors as $error): ?>
                <?php echo htmlspecialchars($error) . "<br>"; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($reminders)): ?>
        <div class="reminder-container">
            <strong>Payment Reminders:</strong><br>
            <?php foreach ($reminders as $reminder): ?>
                <div class="reminder-item reminder-<?php echo $reminder['type']; ?>">
                    <strong><?php echo htmlspecialchars($reminder['name']); ?></strong>
                    Phone: <?php echo htmlspecialchars($reminder['phone']); ?><br>
                    Service <?php echo ($reminder['type'] == 'expired') ? 'expired' : 'expires'; ?> on <?php echo htmlspecialchars($reminder['expiry']); ?>.
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>House Number</th>
                <th>Last Payment Date</th>
                <th>Amount Paid (KSH)</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Reset the result set to the beginning
            if($result){
                $result->data_seek(0);
                // Check if there are any clients
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['house_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['last_payment_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['amount_paid']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['expiry_date']) . "</td>";
                        echo "<td><a href='edit_client.php?id=" . $row['id'] . "' class='btn edit-btn'>Edit</a> |
                                    <a href='delete_client.php?id=" . $row['id'] . "' class='btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this client?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No clients found</td></tr>";
                }
            }else{
                echo "<tr><td colspan='7'>Error loading table information.</td></tr>";
            }

            ?>
        </tbody>
    </table>
</div>

</body>
</html>
