<?php
session_start();

include '../includes/db.php'; // Ensure this path is correct


include ('verifySession.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_password']; // Fixed variable name to match the check below

    // Ensure that the 'id' is present in the session
    if (!isset($_SESSION['user']['id'])) {
        $error = "User session is incomplete.";
    } else {
        $id = $_SESSION['user']['id'];

        // Fetch the current password from the database
        $stmt = $con->prepare("SELECT password FROM admin WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($admin = $result->fetch_assoc()) {
            // Verify the current password and proceed...
            if (!password_verify($currentPassword, $admin['password'])) {
                $error = "Current password is incorrect.";
            } elseif ($newPassword !== $confirmNewPassword) {
                $error = "New passwords do not match.";
            } else {
                // Hash the new password and update it in the database
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $con->prepare("UPDATE admin SET password = ? WHERE id = ?");
                $updateStmt->bind_param("si", $newHashedPassword, $id);
                if ($updateStmt->execute()) {
                    $success = "Password successfully changed.";
                } else {
                    $error = "Failed to change password.";
                }
            }
        } else {
            $error = "Admin account not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../project_root/assets/css/admin.css"> 
    <link
      href="https://fonts.googleapis.com/css?family=Poppins"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body class="password">

<div class="change-password-container">
    <?php if ($error) : ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success) : ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    

    <form action="" method="post">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" name="submit">Change Password</button>
    </form>
</div>

</body>
</html>
