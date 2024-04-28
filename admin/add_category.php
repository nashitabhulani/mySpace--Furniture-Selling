<?php 
session_start();
include('../includes/db.php'); // Ensure this file contains the correct database connection setup
include('verifySession.php');
include('header.php');

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name'])) {
    $name = trim($_POST['name']);

    // Prepare SQL statement to insert data
    if ($stmt = $con->prepare("INSERT INTO category (name) VALUES (?)")) {
        $stmt->bind_param("s", $name);

        // Execute and check if successful
        if ($stmt->execute()) {
            $_SESSION['message'] = "Category added successfully!";
        } else {
            $_SESSION['message'] = "Error adding category: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Failed to prepare the statement.";
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<div class="wrapper">
    <div class="main-content">
        <h1>ADD CATEGORY</h1>
        <br/>
        <?php 
        // Display message from session and clear it
        if(isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        ?>
        <div class="form">
            <form action="" method="POST">
                <table class="table2">
                    <tr>
                        <td>CATEGORY:</td>
                        <td><input type="text" name="name" placeholder="enter category"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="submit" value="ADD CATEGORY" class="button1">
                        </td>   
                    </tr>
                </table>
            </form> 
        </div>
    </div>
</div>
</body>
</html>
