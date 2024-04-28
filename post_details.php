<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$email = $address = "";

// Fetch user details
$query = "SELECT username, email, address FROM users WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $email = $user['email'];
    $address = $user['address'];
} else {
    echo "User details not found.";
}
$stmt->close();

// Fetch total quantity in cart
$stmt = $con->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalQuantity = $row['total_quantity'] ?? 0;
}
$stmt->close();

// Fetch blog post details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $con->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
} else {
    header('Location: index.php');
    exit;
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="project_root/assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="project_root/assets/js/script.js" defer></script>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f4f9;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
        padding-top: 70px; 
    }

    h1 {
        color: #333;
    }

    img {
        display: block;
        max-width: 100%;
        height: auto;
        margin: 20px 0;
    }

    p {
        margin: 20px 0;
    }

    .container {
        margin-top:50px;
        max-width: 800px;
        margin: auto;
        padding: 20px;
        background: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>

</head>
<body>
    <!-- Header start -->
    <div class="header">
      <nav>
        <ul>
        <li><a href="index.php">Home</a></li> 
          <li><a href="shop.php">Shop</a></li>
          <li><a href="blog.php">Review</a></li>
          <li><a href="track.php">Track</a></li>
          <li><a href="ar_view.php">3D view</a></li>
          <li><a href="post.php">Blogs</a></li>
          <!-- <li><a href="admin/index.php">Admin</a></li> -->
        </ul>
      </nav>
    </div>

    <div class="icons">
    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
        <a href="#" id="profile-btn" class="fa-solid fa-user"></a>
    <a href="logout.php" id="logout-btn" class="fa-solid fa-right-from-bracket"></a>
<?php else: ?>
    <a href="login.php" id="login-btn" class="fa-solid fa-user"></a>
<?php endif; ?>
   
      <a href="cart.php" id="cart-btn" class="fa fa-shopping-cart">
      <?php if ($totalQuantity > 0): ?>
        <span class="cart-quantity"><?= $totalQuantity ?></span>
    <?php endif; ?>
      </a>
       </div>

       <div id="profile-box" class="profile-info-box hidden">
  <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
  <p>Email: <?php echo htmlspecialchars($email); ?></p>
  <p>Address: <?php echo htmlspecialchars($address); ?></p>

</div>
<div class="container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <img src="project_root/assets/images/<?= $post['picture'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    </div>
</body>
</html>
