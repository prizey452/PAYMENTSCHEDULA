<?php
include('config/db.php');

// Fetch all clients from the database
$sql = "SELECT * FROM Clients";
$result = $conn->query($sql);

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
    <a href="add_client.php" class="btn add-client-btn">Add New Client</a>  <!-- Button to add new client -->
    <h1>Client List</h1>

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
                              <a href='delete_client.php?id=" . $row['id'] . "' class='btn delete-btn'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No clients found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
