<?php
session_start();
include '../includes/db.php';
include ('verifySession.php');
include ('header.php');
$message = '';

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $con->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Testimonial deleted successfully.";
    } else {
        $message = "An error occurred while deleting the testimonial.";
    }
    $stmt->close();
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

$stmt = $con->prepare("SELECT id, name, testimonial, submitted_at FROM testimonials ORDER BY submitted_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$testimonials = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="wrapper">
       <div class="main-content">
    <h2>Testimonials</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Testimonial</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($testimonials as $testimonial): ?>
                <tr>
                    <td><?php echo $testimonial['id']; ?></td>
                    <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                    <td><?php echo htmlspecialchars($testimonial['testimonial']); ?></td>
                    <td><?php echo $testimonial['submitted_at']; ?></td>
                    <td>
                        <a href="manage_testimonials.php?action=delete&id=<?php echo $testimonial['id']; ?>" onclick="return confirm('Are you sure you want to delete this testimonial?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    
</body>
</html>
