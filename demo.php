<?php
session_start();
include 'includes/db.php';
$totalQuantity = 0; // Assuming there's some logic that sets this based on session or database

// Fetch all products
$sql = "SELECT name, model_name, price FROM products"; // Updated to include price and image for full product display
$result = $con->query($sql);

$products = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
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
</head>
<body>
    <div class="header">
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="track.php">Track</a></li>
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
    <div class="bar">
        <h3>Products</h3>
        <ul>
        <?php foreach ($products as $index => $product): ?>
    <li>
        <p><?= htmlspecialchars($product['name']) ?> - $<?= $product['price'] ?></p>
        <button class="model-button" data-model="project_root/assets/3d models/<?= htmlspecialchars($product['model_name']) ?>"><?= htmlspecialchars($product['name']) ?></button>

    </li>
<?php endforeach; ?>
        </ul>
    </div>
    <!-- Additional HTML for camera feed, etc. -->
    <div id="modelViewer"></div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r126/three.min.js"></script>

<!-- Include GLTFLoader.js (make sure you're using a version compatible with your Three.js version) -->
<script src="https://cdn.jsdelivr.net/npm/three@0.126.1/examples/js/loaders/GLTFLoader.js"></script>


<script src="project_root/assets/js/script.js"></script>
</body>
</html>