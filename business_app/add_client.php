<?php
include('config/db.php');

// Handle form submission to add a new client
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the client details from the form
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $house_number = $_POST['house_number'];
    $payment_date = $_POST['payment_date'];
    $amount_paid = $_POST['amount_paid'];

    // Calculate the expiry date based on the amount paid and the payment date
    if ($amount_paid == 1000) {
        $expiry_date = date('Y-m-d', strtotime('+30 days', strtotime($payment_date)));
    } elseif ($amount_paid == 250) {
        $expiry_date = date('Y-m-d', strtotime('+7 days', strtotime($payment_date)));
    }

    // Insert the new client data into the database
    $sql = "INSERT INTO Clients (name, phone_number, house_number, last_payment_date, amount_paid, expiry_date)
            VALUES ('$name', '$phone_number', '$house_number', '$payment_date', '$amount_paid', '$expiry_date')";

    if ($conn->query($sql) === TRUE) {
        echo "New client added successfully!";
        header("Location: view_clients.php"); // Redirect to client list page after adding
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
    <title>Add New Client</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <a href="view_clients.php" class="home-btn">Home</a>
    <h1>Add New Client</h1>

    <form method="POST" action="">
        <label for="name">Client Name</label>
        <input type="text" name="name" id="name" placeholder="Enter client's name" required><br>

        <label for="phone_number">Phone Number</label>
        <input type="text" name="phone_number" id="phone_number" placeholder="Enter client's phone number" required><br>

        <label for="house_number">House Number</label>
        <input type="text" name="house_number" id="house_number" placeholder="Enter client's house number" required><br>

        <label for="payment_date">Payment Date</label>
        <input type="date" name="payment_date" id="payment_date" required><br>

        <label for="amount_paid">Amount Paid (KSH)</label>
        <select name="amount_paid" id="amount_paid" required>
            <option value="1000">1000 KSH (1 Month)</option>
            <option value="250">250 KSH (1 Week)</option>
        </select><br>

        <input type="submit" value="Add Client">
    </form>
</div>

</body>
</html>
