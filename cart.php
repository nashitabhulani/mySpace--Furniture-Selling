
<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];
$totalPrice = 0;

// Function to update cart
function updateCart($con, $user_id, $product_id, $action)
{
    $stmt = $con->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantity = $row['quantity'];

        if ($action == 'increase' && $quantity < 3) {
            $quantity++;
        } elseif ($action == 'decrease' && $quantity > 1) {
            $quantity--;
        }
        

        $updateStmt = $con->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $updateStmt->bind_param("iii", $quantity, $user_id, $product_id);
        $updateStmt->execute();
    } elseif ($action == 'add') {
        // Your existing 'add' functionality
        $insertStmt = $con->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $insertStmt->bind_param("ii", $user_id, $product_id);
        $insertStmt->execute();
    }
    if ($action == 'delete') {
        $deleteStmt = $con->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $deleteStmt->bind_param("ii", $user_id, $product_id);
        if ($deleteStmt->execute()) {
            // Check if the delete was successful, for example, by checking affected rows.
            if ($deleteStmt->affected_rows == 0) {
                // No rows were deleted, which could mean the item didn't exist in the cart.
                // Handle this case as needed, e.g., log it or notify the user.
                error_log("No items deleted. Possible invalid product_id: $product_id for user_id: $user_id");
            }
        } else {
            // The delete operation failed, handle this case appropriately.
            error_log("Failed to delete product with ID: $product_id for user ID: $user_id");
        }
        $deleteStmt->close();
    }
    // Handle other actions as necessary
}

// Handle adding to the cart
if (isset($_GET['action']) && isset($_GET['product_id'])) {

    error_log("Action: " . $_GET['action'] . ", Product ID: " . $_GET['product_id']);
    $action = $_GET['action'];
    $product_id = (int) $_GET['product_id'];
    updateCart($con, $user_id, $product_id, $action);

    // Redirect to the same page but without the action and product_id parameters
    header('Location: cart.php');
    exit();
}

// Fetch cart items for the user
$cartItems = $con->prepare("SELECT p.name, p.price, p.image, c.quantity, p.id as product_id FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");

$cartItems->bind_param("i", $user_id);
$cartItems->execute();
$cartResult = $cartItems->get_result();
$cartItems = $cartResult->fetch_all(MYSQLI_ASSOC);

$totalItems = count($cartItems);
$canCheckOut = $totalItems > 0 && !array_filter($cartItems, function ($item) {
    return $item['quantity'] > 3;
});

$totalQuantity = array_sum(array_column($cartItems, 'quantity'));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="project_root/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="view">
    <div class="profile-info">
        <p><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION["username"]); ?></p>
        <!-- <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($email); ?></p>
    <p><i class="fas fa-home"></i> <?= htmlspecialchars($address); ?></p> -->
         <a href="logout.php">Log Out</a>
    </div>
    <table class="full">
        <tr>
            <th>Serial no.</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Actions</th>
            <th>Delete</th>
        </tr>
        <?php foreach ($cartItems as $index => $item): ?>
            <tr>
                <td><?= $index + 1; ?></td>
                <td><?= htmlspecialchars($item['name']); ?></td>
                <td><?= $item['price']; ?></td>
                <td><img src="project_root/assets/images/<?= htmlspecialchars($item['image']); ?>"
                        alt="<?= htmlspecialchars($item['name']); ?>" style="width:50px;height:auto;"></td>
                <td><?= $item['quantity']; ?></td>
                <td>
                    <div class="quantity-actions">
                        <a href="cart.php?action=decrease&product_id=<?= $item['product_id']; ?>"
                            class="action-button">-</a>
                        <span class="quantity"><?= $item['quantity']; ?></span>
                        <a href="cart.php?action=increase&product_id=<?= $item['product_id']; ?>"
   class="action-button <?= $item['quantity'] >= 3 ? 'disabled' : '' ?>">+</a>

                    </div>
                </td>
                <td><a href="cart.php?action=delete&product_id=<?= $item['product_id']; ?>" class="action-button"><i
                            class="fa-solid fa-trash"></i></a></td>

            </tr>
        <?php endforeach; ?>
    </table>
    <div class="continue-shopping">
        <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
    </div>

    <div class="sidebar2">
        <div class="detail">
            <strong>Total items: <?php echo count($cartItems); ?></strong>
            <p>Total Quantity: <?= $totalQuantity; ?></p>
            <?php
            foreach ($cartItems as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
                $_SESSION['totalPrice'] = $totalPrice;
            } ?>
            <p>Total Price: &#8377;<?= number_format($totalPrice, 2); ?></p>
        </div>
        <div class="checkout-button">
    <?php if ($canCheckOut): ?>
        <a href="checkout.php" class="checkout-btn">Checkout</a>
    <?php else: ?>
        <p>Cannot checkout, cart is empty or item limit exceeded.</p>
    <?php endif; ?>
</div>

    </div>



</body>

</html>