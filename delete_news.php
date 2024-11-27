<?php
include 'db_connect.php'; // Include your DB connection

// Check if a news_id is provided
if (isset($_GET['id'])) { // Change here from news_id to id
    $news_id = $_GET['id']; // Change here from news_id to id

    // Prepare and execute delete statement
    $stmt = $pdo->prepare("DELETE FROM news WHERE news_id = ?");
    $stmt->execute([$news_id]);

    // Redirect back to the news page after deletion
    header("Location: news_dashboard.php");
    exit;
} else {
    // If no news_id is provided, redirect with an error message
    header("Location: news_dashboard.php?error=No news ID provided");
    exit;
}
?>
