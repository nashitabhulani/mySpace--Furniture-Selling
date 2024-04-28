<?php
session_start();
include 'includes/db.php';
$totalQuantity = 0;
$user_id = $_SESSION['id'];
$email = $address = "";
$query = "SELECT username, email, address FROM users WHERE id = ?";
if($stmt = mysqli_prepare($con, $query)){
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1){                    
            mysqli_stmt_bind_result($stmt, $username, $email, $address);
            if(!mysqli_stmt_fetch($stmt)){
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
    mysqli_stmt_close($stmt);
}
$stmt = $con->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalQuantity = $row['total_quantity'] ? $row['total_quantity'] : 0;
}
$sql = "SELECT id, title, summary, picture FROM blog_posts ORDER BY created_at DESC";
$result = $con->query($sql);

$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
} else {
    echo "No posts found.";
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture Insights</title>
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
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <script src="project_root/assets/js/script.js" defer></script>
    <title>Blog</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background: #f4f4f4;
    color: #333;
    header {
    background: #333;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
}
header {
    background: #4a4e69;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
    margin-top:90px;
}
h1 {
    margin: 0;
}

#postsContainer {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
}

.post {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow 0.3s ease-in-out;
}

.post:hover {
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.post img {
    width: 100%;
    height: auto;
    display: block;
}

.post-content {
    padding: 15px;
}

.post-title {
    font-size: 20px;
    margin: 0 0 10px;
}

.post-summary {
    font-size: 16px;
    line-height: 1.5;
    color: #666;
}

button {
    background: #4a4e69;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    width: 100%;
    display: block;
}

button:hover {
    background: #9a8c98;
}
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

    <header>
        <h1>Furniture Insights</h1>
    </header>
 

    <div id="postsContainer">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <img src="project_root/assets/images/<?= $post['picture'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                <div class="post-content">
                    <h2 class="post-title"><?= htmlspecialchars($post['title']) ?></h2>
                    <p class="post-summary"><?= htmlspecialchars($post['summary']) ?></p>
                    <button onclick="location.href='post_details.php?id=<?= $post['id'] ?>'">Read More</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
           
<footer class="site-footer">
    <div class="footer-content">
        <p>Follow us on Social Media</p>
        <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.twitter.com" target="_blank"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.linkedin.com" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
        <p class="footer-text">© 2024 Your Company. All rights reserved.</p>
    </div>
</footer>



</body>
</html>
