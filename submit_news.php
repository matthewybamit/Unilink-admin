<?php
include 'db_connect.php'; // Include your DB connection

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = isset($_POST['news_id']) ? $_POST['news_id'] : null;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $image_url = $_POST['image_url']; // Assuming you're still using the URL field

    // If news_id is present, update existing news; otherwise, insert new news
    if ($news_id) {
        // Update news item
        $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, author = ?, image_url = ? WHERE news_id = ?");
        $stmt->execute([$title, $content, $author, $image_url, $news_id]);
    } else {
        // Insert new news item
        $stmt = $pdo->prepare("INSERT INTO news (title, content, author, image_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $author, $image_url]);
    }

    // Redirect back to the news page after success
    header("Location: news_dashboard.php");
    exit;
}
?>
