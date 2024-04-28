<?php
session_start();
include 'includes/db.php';

// Check if the form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $user['id'];
                    $_SESSION["username"] = $user['username'];
                    header("Location: index.php");
                    exit;
                } else {
                    $login_err = "Invalid username or password.";
                }
            } else {
                $login_err = "Invalid username or password.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}

// Check if the form is submitted for registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'register') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $address = $_POST['address'];

    $sql = "INSERT INTO users (username, email, password, address) VALUES (?, ?, ?, ?)";
    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $email, $password, $address);
        if ($stmt->execute()) {
            header("Location: login.php?registered=true");
            exit;
        } else {
            echo "Error: " . $con->error;
        }
        $stmt->close();
    }
}
$con->close();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>mySpace-Login</title>
    <link rel="stylesheet" href="project_root/assets/css/style.css" />
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
    <script src="project_root/assets/js/script.js" defer></script>
  </head>
  <body>
    <section class="account">
      <div class="container">
        <div class="row">
          <div class="col-2"></div>
          <div class="col-2">
            <div class="form-container">
              <div class="form-btn">
                <span onclick="login()">Login</span>
                <span onclick="register()">Register</span>
                <hr id="Indicator" />
              </div>
              <!-- <form id="LoginForm" action="login.php" method="post">
  <input type="text" name="username" placeholder="Username" required />
  <input type="password" name="password" placeholder="Password" required />
  <button type="submit" class="btn">Login</button>
  <a href="#">Forgot Password</a>
</form>

              <form id="RegForm" action="login.php" method="post">
                <input type="text" placeholder="Username" />
                <input type="email" placeholder="Email" />
                <input type="password" placeholder="Password" />
                 <input type="text" name="" id="" placeholder="Address" /> 
                <textarea  cols="60" rows="5" name="description"  placeholder="Address"></textarea>
                <button type="submit" class="btn">Register</button>
              </form> -->
               <!-- Login Form -->
    <form id="LoginForm" action="login.php" method="post">
        <input type="hidden" name="action" value="login">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
    </form>

    <!-- Registration Form -->
    <form id="RegForm" action="login.php" method="post">
        <input type="hidden" name="action" value="register">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <textarea cols="60" rows="5" name="address" placeholder="Address" required></textarea>
        <button type="submit" class="btn">Register</button>
    </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>
