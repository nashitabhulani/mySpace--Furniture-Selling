<?php
session_start();
include '../includes/db.php'; 

include ('verifySession.php');
include ('header.php');

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];
    $created_at = date("Y-m-d H:i:s");

    // Handle file upload
    $picture = $_FILES['picture']['name'];
    $target_dir = "../project_root/assets/images/";
    $target_file = $target_dir . basename($picture);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {
        // Upload file
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
            // Insert record
            $stmt = $con->prepare("INSERT INTO blog_posts (title, summary, content, picture, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $summary, $content, $picture, $created_at);
            $stmt->execute();
            $stmt->close();
        }
    }

    $con->close();
    header('Location: view_articles.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Article</title>
    <link rel="stylesheet" href="css/admin.css"> <!-- Path to your CSS file -->
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <h1>Add New Article</h1>
        <form action="add_article.php" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br>

            <label for="summary">Summary:</label><br>
            <textarea id="summary" name="summary" rows="2" required></textarea><br>

            <label for="content">Content:</label><br>
            <textarea id="content" name="content" rows="5" required></textarea><br>

            <label for="picture">Picture:</label><br>
            <input type="file" id="picture" name="picture" required><br>

            <button type="submit">Submit</button>
        </form>
    </div>
</div>
</body>
</html>
