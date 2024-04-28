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
if(isset($_POST['submit_testimonial'])) {
  $name = $con->real_escape_string($_POST['name']);
  $testimonial = $con->real_escape_string($_POST['testimonial']);
  $submitted_at = date('Y-m-d H:i:s'); // Capture the current date and time

  // Prepare the insert statement
  $stmt = $con->prepare("INSERT INTO testimonials (name, testimonial, submitted_at) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $testimonial, $submitted_at);

  // Execute the statement and check if successful
  if($stmt->execute()) {
      // Use a session variable to pass the success message to the redirected page
      $_SESSION['message'] = "Testimonial submitted successfully!";
  } else {
      // Pass an error message in the same way
      $_SESSION['error'] = "Error submitting testimonial.";
  }

  $stmt->close();

  // Redirect to the same page to avoid form resubmission issues
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
  
}

// Retrieve testimonials
$stmt = $con->prepare("SELECT name, testimonial, submitted_at FROM testimonials ORDER BY submitted_at DESC LIMIT 3");

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch all testimonials
$testimonials = $result->fetch_all(MYSQLI_ASSOC);

// Don't forget to free the result and close the statement if they are no longer needed
$result->free();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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

   <!-- <h1>Articles</h1>
    <div class="articles">
        <?php foreach ($articles as $article): ?>
            <div class="article">
                <h2><?= htmlspecialchars($article['title']) ?></h2>
                <?php if ($article['picture']): ?>
                    <img src="<?= htmlspecialchars($article['picture']) ?>" alt="Article Image" style="max-width: 100%; height: auto;">
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
                <span><?= htmlspecialchars(date("F j, Y", strtotime($article['created_at']))) ?></span>
            </div>
        <?php endforeach; ?>
    </div> -->

<div class="container1"> 
    <h1 id="line">Testimonials</h1> <!-- Adjust the margin-top as needed -->

    <div class="testimonials">
        <?php foreach ($testimonials as $testimonial): ?>
            <div class="testimonial">
                <blockquote><?= nl2br(htmlspecialchars($testimonial['testimonial'])) ?></blockquote>
                <cite>- <?= htmlspecialchars($testimonial['name']) ?>, <?= htmlspecialchars(date("F j, Y", strtotime($testimonial['submitted_at']))) ?></cite>
            </div>
        <?php endforeach; ?>
    </div>
        </div>

    <div class="image">
    <div class="testimonial-submission">
    <h2>Submit Your Testimonial</h2>
    <form action="blog.php" method="post">
        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="testimonial">Your Testimonial:</label>
            <textarea id="testimonial" name="testimonial" rows="5" required></textarea>
        </div>
        <button type="submit" name="submit_testimonial">Submit Testimonial</button>
    </form>
</div>
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