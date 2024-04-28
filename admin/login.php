<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    <style>
      body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f4f4f2; /* Light grey background */
}
    </style>
</head>
<body>
   
<div class="login-page">
     <h1>Login Page</h1>
     <br><br>
               <?php
               if(isset($_SESSION['login']))
                {
                  echo $_SESSION['login'];
                  unset ($_SESSION['login']);
                }
                if(isset($_SESSION['not-logged'])){
                    echo $_SESSION['not-logged'];
                  unset ($_SESSION['not-logged']);
                }
                ?>
                <br>
     <form action="" method="POST" class="text">
        <p class="text">Username:</p>
        <input type="text" name="username" placeholder="Username" class="text">
        <br><br>
        <p class="text">Password: </p>
        <input type="password" name="password" placeholder="Password" class="text">
<br><br>
        <input type="submit"name="submit" value="Login" class="button2">


     </form>
    </div>
</body>
</html>

<?php
  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    // Don't hash the password here; we'll verify it later using password_verify()
    

    // Prepare statement to prevent SQL Injection
    $stmt = $con->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Now verify the password
        if (md5($_POST['password'], $user['password'])) {
            $_SESSION['login'] = "Login successful.";
            // It's better to store user ID or any other necessary info but not password
            $_SESSION['user'] = ['id' => $user['id'], 'username' => $username];
            header("Location: ../admin/index.php");
            exit;
        } else {
            $_SESSION['login'] = "Login not successful. Incorrect username or password.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login'] = "Login not successful. User does not exist.";
        header("Location: login.php");
        exit;
    }
}


?>
</body>
</html>