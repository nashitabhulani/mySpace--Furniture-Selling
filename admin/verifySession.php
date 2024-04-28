<?php


// Check if the user is logged in, using the session user info
if (!isset($_SESSION['user'])) {
    // User is not logged in, redirect to login page
    $_SESSION['not-logged'] = "<div class='error'>Please login to access the admin panel.</div>";
    header("location: login.php");
    exit; // Ensure script execution ends here
}
?>
