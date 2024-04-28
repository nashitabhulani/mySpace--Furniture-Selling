<?php
session_start();
include 'includes/db.php';

$totalQuantity=0;
$user_id = $_SESSION['id'];
$email = $address = "";
$categories = [];
$result = $con->query("SELECT id, name FROM category");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
$stmt = $con->prepare("SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalQuantity = $row['total_quantity'] ? $row['total_quantity'] : 0;
}
$sql = "SELECT id, name, image, model_name FROM products";
$result = $con->query($sql);

$models = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $models[] = $row;
    }
} else {
    echo "0 results";
}
$con->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>AR Product Viewer</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="project_root/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@google/model-viewer"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.126.1/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three/examples/js/loaders/GLTFLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three/examples/js/controls/OrbitControls.js"></script>
    <script src="project_root/assets/js/script.js"></script>
    <style>
    /* Basic Reset */
    body, h1, h2, h3, div, span, p {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        padding-top: 50px;
    }
    </style>
</head>
<body>

    <div class="header">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
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

<div class="content-container">
        <div class="sidebar1">
            <!-- Loop through models for product details -->
            <?php foreach ($models as $model): ?>
                <div class="model-card">
                    <h3><?= $model['name'] ?></h3>
                    <!-- <p>Model: <?= $model['model_name'] ?></p> -->
                    <button onclick="loadModel('<?= $model['model_name'] ?>', '<?= $model['name'] ?>')">View in 3D</button>
                    <button onclick="viewInAR('<?= $model['model_name'] ?>')">Download</button>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="main-viewer">
            <!-- 3D Viewer will be displayed here -->
            
<model-viewer id="modelViewer" style="display: none;" camera-controls auto-rotate ar></model-viewer>
            <div id="threeJSViewer" style="width: 100%; height: 500px;"></div>
        </div>
    </div>

<script src="project_root/assets/js/script.js"></script>
</body>
</html>