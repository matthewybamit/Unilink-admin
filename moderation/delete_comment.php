<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = intval($_POST['comment_id']);
    $moderator = 'admin';

    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);

    $logStmt = $pdo->prepare("INSERT INTO moderation_logs (action, moderator) VALUES (?, ?)");
    $logStmt->execute(["Deleted comment ID $commentId", $moderator]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
