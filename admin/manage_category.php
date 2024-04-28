<?php
session_start();
include('../includes/db.php'); // Ensure proper database connection setup
include('verifySession.php');
include('header.php');

// Handle deletion of category
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $con->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Category deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting category.";
    }
    $stmt->close();
    header("Location: manage_category.php");
    exit;
}

// Fetch categories from the database
$result = $con->query("SELECT id, name FROM category");
?>

<div class="wrapper">
    <div class="main-content">
        <h1>MANAGE CATEGORIES</h1>
        <br/>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div>' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>
                        <a href="manage_category.php?delete=<?php echo $row['id']; ?>"  class= "btn" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
