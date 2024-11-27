<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    echo "error: not logged in";
    exit();
}

// Database connection
$host = 'localhost';
$db = 'unilink_database';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if post_id is provided
if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Prepare DELETE statement
    $stmt = $conn->prepare("DELETE FROM forum_posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);  // Bind the post ID as an integer
    $stmt->execute();

    // Check if the post was deleted successfully
    if ($stmt->affected_rows > 0) {
        echo 'success';
    } else {
        echo 'error: could not delete post';
    }

    $stmt->close();
} else {
    echo 'error: no post ID provided';
}

$conn->close();
?>
