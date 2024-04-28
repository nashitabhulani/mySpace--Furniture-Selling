<?php

session_start();
include '../includes/db.php'; 
include ('verifySession.php');
include ('header.php');

// Handle user deletion
if(isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_stmt = $con->prepare("DELETE FROM users WHERE id = ?");
    $delete_stmt->bind_param("i", $delete_id);
    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully";
    } else {
        $_SESSION['message'] = "User deletion failed";
    }
    header("Location: index.php.php");
    exit();
}

// Fetch all users
$query = "SELECT * FROM users";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - View Users</title>
    <link rel="stylesheet" href="../project_root/assets/css/admin.css">
</head>
<body>
    <div class="wrapper">
    <div class="main-content">
      
    
      <br/><br/> 
        <h2>User Management</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>
        <table class="full">
            <tr>
                <th>Serial no.</th>
                <th>Full name</th>
                <th>User Name</th>
                <th>Actions</th>
            </tr>
            <?php
            $serial_no = 1;
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $serial_no++ . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>"; 
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>
                       
                <a href='?delete_id=" . $row['id'] . "'  onclick='return confirm(\"Are you sure?\") ;'>Delete</a>

                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
