<?php 

// Start the session
session_start();

// Include database connection file
include('../includes/db.php');

// Check if the id is set in the URL
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the ID of the product to be deleted
    $id = $_GET['id'];

    // SQL query to delete the product from the database
    $sql = "DELETE FROM products WHERE id = $id";

    // Execute the query
    $res = mysqli_query($con, $sql);

    // Check whether the query executed successfully
    if($res == true) {
        // Product deleted
        $_SESSION['delete'] = "<div>Product Deleted Successfully.</div>";
    } else {
        // Failed to delete product
        $_SESSION['delete'] = "<div>Failed to Delete Product. Please Try Again Later.</div>";
    }
} else {
    // Redirect to the manage product page with an error message if the id isn't set
    $_SESSION['delete'] = "<div>Unauthorized Access.</div>";
}

// Redirect to manage products page
header('location: view-products.php');

?>
