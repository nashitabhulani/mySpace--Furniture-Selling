<?php
session_start();
include '../includes/db.php'; // Adjust the path as necessary

include ('verifySession.php')

// Validate POST data
if(isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $orderId = mysqli_real_escape_string($con, $_POST['order_id']);
    $orderStatus = mysqli_real_escape_string($con, $_POST['order_status']);

    // Update order status in the database
    $sql = "UPDATE orders SET order_status=? WHERE order_id=?";
    $stmt = mysqli_prepare($con, $sql);
    
    // Check if statement was prepared successfully
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $orderStatus, $orderId);
        mysqli_stmt_execute($stmt);

        // Check if update was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['status_updated'] = "Order status updated successfully.";
        } else {
            $_SESSION['status_update_failed'] = "Failed to update order status.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare the statement.";
    }
} else {
    echo "Invalid request.";
}

header("Location: view_orders.php"); // Redirect back to the view_orders.php page
exit;
?>