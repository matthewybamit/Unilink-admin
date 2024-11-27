<?php
// Example of handling the moderation actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'];
    $userId = (int)$data['userId'];
    
    try {
        switch ($action) {
            case 'ban':
                // Update user status to 'banned' in the database
                $stmt = $pdo->prepare("UPDATE users SET status = 'Banned' WHERE id = ?");
                $stmt->execute([$userId]);
                echo json_encode(['status' => 'success']);
                break;

            case 'unban':
                // Update user status to 'active' in the database
                $stmt = $pdo->prepare("UPDATE users SET status = 'Active' WHERE id = ?");
                $stmt->execute([$userId]);
                echo json_encode(['status' => 'success']);
                break;

            case 'reset_password':
                // Reset the user's password (you may want to generate a random password or send a reset email)
                $newPassword = bin2hex(random_bytes(8)); // Generate random password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $userId]);

                // Optionally, send the new password to the user
                echo json_encode(['status' => 'success']);
                break;

            default:
                echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
