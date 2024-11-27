<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = intval($_POST['user_id']);


    $stmt = $pdo->prepare("UPDATE users SET status = 'banned' WHERE id = ?");
    $stmt->execute([$userId]);

    $logStmt = $pdo->prepare("INSERT INTO moderation_logs (action, moderator) VALUES (?, ?)");
    $logStmt->execute(["Banned user ID $userId", $moderator]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
