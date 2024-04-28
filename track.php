<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Retrieve the logged-in user's ID
$user_id = $_SESSION['id'];
$email = $address = "";
$query = "SELECT username, email, address FROM users WHERE id = ?";
if ($stmt = mysqli_prepare($con, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_bind_result($stmt, $username, $email, $address);
            if (!mysqli_stmt_fetch($stmt)) {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    } else {
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
$totalQuantity = 0;

$sql = "SELECT orders.order_id, orders.total_price, orders.order_status, orders.created_at
        FROM orders
        WHERE orders.user_id = ?
        ORDER BY orders.created_at DESC";

if ($stmt = $con->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {

    echo "Error: " . $con->error;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .order {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .order h3 {
            margin-top: 0;
        }
    </style>
    <link rel="stylesheet" href="project_root/assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            </ul>
        </nav>
    </div>

    <div class="icons">
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
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
    <h2>Your Orders</h2>

    <?php if (empty($orders)): ?>
        <p>You have no orders.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="orders-container">
                <div class="order">
                    <h3>Order ID: <?= htmlspecialchars($order['order_id']) ?></h3>
                    <p>Total Price: &#8377;<?= htmlspecialchars($order['total_price']) ?></p>
                    <p>Status: <?= htmlspecialchars($order['order_status']) ?></p>
                    <p>Order Date: <?= htmlspecialchars($order['created_at']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>