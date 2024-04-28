<?php
session_start();
include 'includes/db.php';
// Products query
$productsQuery = "SELECT id, name, description, price, image FROM products WHERE id IN (6, 7, 8)";
$productsResult = $con->query($productsQuery);
$totalQuantity = 0;
if (!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit();
}

$user_id = $_SESSION['id'];
$email = '';
$address = '';
$username = '';

// Fetch user details from the database
$query = "SELECT username, email, address FROM users WHERE id = ?";
if ($stmt = mysqli_prepare($con, $query)) {
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $username, $email, $address);
  if (mysqli_stmt_fetch($stmt)) {
      // Data fetched successfully
  } else {
      echo "Oops! Something went wrong. Please try again later.";
  }
  mysqli_stmt_close($stmt);
} else {
  echo "Oops! Something went wrong. Please try again later.";
}

// Calculate cart total quantity
$cartStmt = $con->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?");
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();
$totalQuantity = 0;
if ($cartRow = $cartResult->fetch_assoc()) {
  $totalQuantity = $cartRow['total_quantity'] ?: 0;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>mySpace</title>
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



    <div id="section1">
      <div class="home">
        
      </div>
    </div>
    <!-- section 1  -->

    <!-- section 2 begin -->
    <div id="section2">
      <div class="products">
        <h2>TOP CATEGORY</h2>
        <div class="content">
        <a href="shop.php?category=chair">
          <img src="project_root/assets/images/Contour drawing of a chair.jpeg" alt="chair" />
          <div class="description"><span>Chairs</span></div>
        </div>

        <div class="content">
        <a href="shop.php?category=table">
          <img src="project_root/assets/images/line image table.jpg" alt="chair" />
          <div class="description"><span>Tables</span></div>
        </div>

        <div class="content">
        <a href="shop.php?category=sofa">
          <img
            src="project_root/assets/images/Premium Vector _ Line art illustrator outline of a sofa for living room vector illustration_ vector design elements_.jpeg"
            alt="chair"
          />
          <div class="description"><span>Sofas</span></div>
        </div>

        <div class="content">
        <a href="shop.php?category=bed">
          <img
            src="project_root/assets/images/Hand Drawn Lines Vector PNG Images, Cartoon Side Hand Drawn Bed Line Draft, Bed Clipart, Black And White, Cartoon Hand Drawn Bed Line Drawing PNG Image For Free Download.jpeg"
            alt="chair"
          />
          <div class="description"><span>Bedroom</span></div>
        </div>

        <div class="content">
        <a href="shop.php?category=wardrobe"> 
          <img src="project_root/assets/images/Lamp stand on white background.jpeg" alt="chair" />
          <div class="description"><span>Other</span></div>
        </div></a>
      </div>
    </div>
<br><br>
    <!-- top products begin  -->
    <!-- Corrected products section -->
   
    <h2 class="heading1">Our best selling products</h2>
    <div class=" home-products-container">
        <div class="row">
        <?php while ($row = mysqli_fetch_assoc($productsResult)): ?>
                <div class="col-4">
                    <img src="project_root/assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100%;">
                    <div class="box">
                        <h3><?= htmlspecialchars($row['name']); ?></h3>
                        <ul class="details">
                            <?php foreach (explode("\n", $row['description']) as $description): ?>
                                <li><span><?php echo htmlspecialchars($description); ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price">
                            <strong>₹<?= htmlspecialchars($row['price']); ?></strong>
                            <a href="cart.php?action=add&product_id=<?= htmlspecialchars($row['id']); ?>"><button>Add to Cart</button></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    </div>



    <!-- section 4 begin -->
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
    <!-- section 4 end -->
  </body>
</html>
