<?php
include('config/db.php');

// Check if client ID is provided in the URL
if (isset($_GET['id'])) {
    $client_id = $_GET['id'];

    // Fetch client details from the database
    $sql = "SELECT * FROM Clients WHERE id = '$client_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        echo "Client not found!";
        exit;
    }
} else {
    echo "Invalid client ID!";
    exit;
}

// Handle form submission to update client details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated client details from the form
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $house_number = $_POST['house_number'];
    $new_payment_date = $_POST['new_payment_date'];
    $amount_paid = $_POST['amount_paid'];

    // Calculate the new expiry date based on the amount paid and the new payment date
    if ($amount_paid == 1000) {
        $expiry_date = date('Y-m-d', strtotime('+30 days', strtotime($new_payment_date)));
    } elseif ($amount_paid == 250) {
        $expiry_date = date('Y-m-d', strtotime('+7 days', strtotime($new_payment_date)));
    }

    // Update the client details in the database
    $sql_update = "UPDATE Clients 
                   SET name = '$name', phone_number = '$phone_number', house_number = '$house_number', 
                       last_payment_date = '$new_payment_date', amount_paid = '$amount_paid', expiry_date = '$expiry_date' 
                   WHERE id = '$client_id'";

    if ($conn->query($sql_update) === TRUE) {
        echo "Client details updated successfully!";
        header("Location: view_clients.php"); // Redirect back to the client list page after update
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <a href="view_clients.php" class="home-btn">Home</a>
    <h1>Edit Client: <?php echo htmlspecialchars($client['name']); ?></h1>

    <form method="POST" action="">
        <label for="name">Client Name</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($client['name']); ?>" required><br>

        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($client['phone_number']); ?>" required><br>

        <label for="house_number">House Number</label>
        <input type="text" name="house_number" id="house_number" value="<?php echo htmlspecialchars($client['house_number']); ?>" required><br>

        <label for="new_payment_date">New Payment Date</label>
        <input type="date" name="new_payment_date" id="new_payment_date" value="<?php echo htmlspecialchars($client['last_payment_date']); ?>" required><br>

        <label for="amount_paid">Amount Paid (KSH)</label>
        <select name="amount_paid" id="amount_paid" required>
            <option value="1000" <?php echo ($client['amount_paid'] == 1000) ? 'selected' : ''; ?>>1000 KSH (1 Month)</option>
            <option value="250" <?php echo ($client['amount_paid'] == 250) ? 'selected' : ''; ?>>250 KSH (1 Week)</option>
        </select><br>

        <input type="submit" value="Update Client">
    </form>
</div>

</body>
</html>
