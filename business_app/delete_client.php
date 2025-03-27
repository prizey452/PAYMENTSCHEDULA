<?php
include('config/db.php');

if (isset($_GET['id'])) {
    $client_id = $_GET['id'];

    // Delete related payments first
    $delete_payments_sql = "DELETE FROM Payments WHERE client_id = '$client_id'";
    if ($conn->query($delete_payments_sql) === TRUE) {
        // Now delete the client
        $delete_client_sql = "DELETE FROM Clients WHERE id = '$client_id'";
        if ($conn->query($delete_client_sql) === TRUE) {
            echo "<div class='alert alert-success'>Client and related payments deleted successfully!</div>";
            header('Location: view_clients.php');
            exit();
        } else {
            echo "<div class='alert alert-error'>Error deleting client: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-error'>Error deleting payments: " . $conn->error . "</div>";
    }

} else {
    echo "<div class='alert alert-error'>Client not found.</div>";
    exit();
}

$conn->close();
?>
