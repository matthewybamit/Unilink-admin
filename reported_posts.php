<?php
// Initialize the variable to avoid undefined variable warning
$reported_posts = [];

// Include the database connection
require_once 'db_connect.php'; 

// Get current page number, default is 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;  // Number of results per page
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// SQL query to fetch reported posts along with reporter details (using user_id as reporter)
$sql = "SELECT rp.*, u.username AS reported_username, u.profile_picture AS user_image, r.username AS reporter_username, r.profile_picture AS reporter_image
        FROM post_reports rp
        JOIN users u ON rp.user_id = u.id  -- This is the user whose post was reported
        JOIN users r ON rp.user_id = r.id  -- This is the reporter (user who made the report)
        WHERE u.username LIKE :search
        ORDER BY rp.reported_at DESC
        LIMIT :limit OFFSET :offset";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Fetch results into the $reported_posts array
$reported_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of reported posts for pagination
$count_sql = "SELECT COUNT(*) FROM post_reports rp
              JOIN users u ON rp.user_id = u.id
              JOIN users r ON rp.user_id = r.id
              WHERE u.username LIKE :search";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->bindValue(':search', '%' . $search . '%');
$count_stmt->execute();
$total_reports = $count_stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_reports / $limit);
?>
