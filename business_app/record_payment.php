<?php
include('config/db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = date('Y-m-d');  // Current date

    // Calculate the expiry date based on the amount paid
    if ($amount_paid == 1000) {
        $expiry_date = date('Y-m-d', strtotime('+30 days', strtotime($payment_date)));
    } elseif ($amount_paid == 250) {
        $expiry_date = date('Y-m-d', strtotime('+7 days', strtotime($payment_date)));
    } else {
        echo "Invalid payment amount!";
        exit;
    }

    // Update the client's payment info in the database
    $sql = "UPDATE Clients 
            SET last_payment_date = '$payment_date', 
                amount_paid = '$amount_paid', 
                expiry_date = '$expiry_date' 
            WHERE id = '$client_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Payment recorded successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

?>
