<?php
session_start();
include 'includes/db.php'; // Adjust the path as necessary

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['id'];
$order_placed = false; // Initialize variable

$totalPrice = 0;

// Fetch cart items
$cartItems = $con->prepare("SELECT p.price, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$cartItems->bind_param("i", $user_id);
$cartItems->execute();
$result = $cartItems->get_result();
$cartItems = $result->fetch_all(MYSQLI_ASSOC);

foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Check if the form was submitted
if (isset($_POST['confirmed'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Here, insert the order details into your orders table
    $insertOrder = $con->prepare("INSERT INTO orders (user_id, name, phone, email, address, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $insertOrder->bind_param("issssd", $user_id, $name, $phone, $email, $address, $totalPrice);
    $insertOrderSuccess = $insertOrder->execute();

    if ($insertOrderSuccess) {
        // If order is recorded successfully, empty the cart
        $emptyCart = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $emptyCart->bind_param("i", $user_id);
        $emptyCart->execute();
        
        $order_placed = true; // Update the order placed status
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="project_root/assets/css/style.css" />
<link
      href="https://fonts.googleapis.com/css?family=Poppins"
      rel="stylesheet"
    />
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body class="checkout">

<?php if (!$order_placed): ?>
    <div class="checkout-heading">
    <h2>Checkout</h2>
    <p class="total-price">Total Price: $<?= number_format($totalPrice, 2); ?></p>
</div>
<div class="checkout-box">
    <form action="checkout.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" required>

        <label for="email">Email ID:</label>
        <input type="email" id="email" name="email" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <input type="hidden" name="confirmed" value="true">
        <input type="submit" value="Confirm and Pay">
    </form>
</div>
<p><a href="shop.php">Continue Shopping</a></p>
<?php else: ?>
<div class="confirmation-message">
    <p>Your order has been placed successfully!</p>
    <p>Cart cleared.</p>
    <p><a href="shop.php">Continue Shopping</a></p>
</div>
<?php endif; ?>

</body>
</html>
