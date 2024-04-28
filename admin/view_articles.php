<?php
session_start();
include '../includes/db.php'; 

include ('verifySession.php');
include ('header.php');


// Delete article
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $con->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: view_articles.php');
    exit;
}

// Fetch all articles
$articles = [];
$stmt = $con->prepare("SELECT id, title FROM blog_posts");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $articles[] = $row;
}
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Path to your CSS file -->
</head>
<body>
<div class="wrapper">
       <div class="main-content">
    <h1>Article Management</h1>
    <a href="add_article.php" class="add">Add New Article</a>
    <br/><br/> <br/>
    <table>
        <tr>
            <th>Title</th>
            <th>Action</th>
        </tr>
        <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= htmlspecialchars($article['title']) ?></td>
            <td>
                <a href="view_articles.php?delete=<?= $article['id'] ?>" class="btn"onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
        </div>
        </div>
</body>
</html>
