<?php
session_start();
include 'includes/db.php';
// header('Location: shop.php'); // Redirect back to shop
// exit();
$totalQuantity = 0;
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    // Assuming you've already connected to your database ($con)
    $user_id = $_SESSION['id']; // Get the logged-in user's ID

    $email = $address = "";

    // Check if user is logged in
    
        $user_id = $_SESSION['id']; // Get the logged-in user's ID
    
        // Fetch user details from the database
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
}
 
$categoryName = isset($_GET['category']) ? mysqli_real_escape_string($con, $_GET['category']) : null;
$searchTerm = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : null;

if ($categoryName) {
    $categoryQuery = "SELECT id FROM category WHERE name = ?";
    $categoryStmt = mysqli_prepare($con, $categoryQuery);
    mysqli_stmt_bind_param($categoryStmt, "s", $categoryName);
    mysqli_stmt_execute($categoryStmt);
    $categoryResult = mysqli_stmt_get_result($categoryStmt);
    $categoryId = mysqli_fetch_assoc($categoryResult)['id'] ?? null;
    
    if ($categoryId) {
        if ($searchTerm) {
            $query = "SELECT id, name, description, price, image FROM products WHERE category_id = ? AND name LIKE ? ORDER BY id";
            $searchTerm = "%$searchTerm%";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "is", $categoryId, $searchTerm);
        } else {
            $query = "SELECT id, name, description, price, image FROM products WHERE category_id = ? ORDER BY id";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "i", $categoryId);
        }
    } else {
        $query = "SELECT id, name, description, price, image FROM products ORDER BY id";
        $stmt = mysqli_prepare($con, $query);
    }
} else {
    if ($searchTerm) {
        $query = "SELECT id, name, description, price, image FROM products WHERE name LIKE ? ORDER BY id";
        $searchTerm = "%$searchTerm%";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $searchTerm);
    } else {
        $query = "SELECT id, name, description, price, image FROM products ORDER BY id";
        $stmt = mysqli_prepare($con, $query);
    }
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
    <title>Shop</title>
<link rel="stylesheet" href="project_root/assets/css/style.css">
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
  <div class="sidebar">
  <div class="form">
    <form action="shop.php" method="get">
        <input type="text" id="search" name="search" placeholder="Search..." />
        <?php if ($categoryName): ?>
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categoryName); ?>">
        <?php endif; ?>
        <button class="submit" type="submit">Search</button>
    </form>
</div>
<br><br>
  <div class="row-categories">
    <h3>Categories</h3>
    <ul>
    <li><a href="shop.php">All rows</a></li>
    <li><a href="shop.php?category=sofa">Sofas</a></li>
        <li><a href="shop.php?category=chair">Chairs</a></li>
        <li><a href="shop.php?category=table">Tables</a></li>
        <li><a href="shop.php?category=wardrobe">Wardrobe</a></li>
        <li><a href="shop.php?category=bed">Beds</a></li>
        <li><a href="shop.php?category=Decoration">Decoration</a></li>
      <!-- Add more categories as needed -->
    </ul>
  </div>
</div>

    <div class="main-content">
      <div class="header">
          <nav>
              <ul>
              <li><a href="index.php">Home</a></li>
          <!-- <li><a href="#section2">Products</a></li> -->
          <li><a href="shop.php">Shop</a></li>
          <li><a href="blog.php">Review</a></li>
          <li><a href="track.php">Track</a></li>
          <li><a href="ar_view.php">3D view</a></li>
          <li><a href="post.php">Blogs</a></li>
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
    <!-- Your sidebar and other content -->

    <div class="main-content">
        <!-- Other content before your rows -->
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-4">
                <img src="project_root/assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:100%;">
                    <div class="box">
                        <h3><?= $row['name']; ?></h3>
                       
                        <ul class="details">
              <!-- Assuming your description contains details separated by new lines -->
              <?php foreach(explode("\n", $row['description']) as $description): ?>
                <li><span><?php echo htmlspecialchars($description); ?></span></li>
              <?php endforeach; ?>
            </ul>
                        <div class="price">
                            <strong>₹<?= $row['price']; ?></strong>
                            <!-- <a href="cart.php?action=add&product_id=<?= $product['id']; ?>">Add to Cart</a> -->
                            <a href="cart.php?action=add&product_id=<?= $row['id']; ?>"><button>Add to Cart</button></a>


 


                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

