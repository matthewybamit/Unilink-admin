<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Redirect to login if not logged in
    header("Location: Admin_login.php");
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

// Fetch posts, comments, users, hashtags, reports, and logs from the database
$posts = $conn->query("SELECT * FROM forum_posts");
$comments = $conn->query("SELECT * FROM comments");
$users = $conn->query("SELECT * FROM users");
$hashtags = $conn->query("SELECT * FROM hashtags");
$logs = $conn->query("SELECT * FROM moderation_logs");
$reports = $conn->query("SELECT * FROM reports");

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/nav.css">
</head>

<style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
            margin: 5px;
            border: none;
            cursor: pointer;
        }
        .resolved {
            background-color: green;
            color: white;
        }
        .dismissed {
            background-color: red;
            color: white;
        }
        .pending {
            background-color: yellow;
            color: black;
        }
    </style>
<body>
<!-- MODERATION DASHBOARD -->
<div class="moderation-dashboard">
    <h1>Moderation Panel</h1>
    <div class="moderation-categories">
        <!-- Navigation Menu -->
        <ul>
            <li><button onclick="showSection('moderate-posts')">Posts</button></li>
            <li><button onclick="showSection('moderate-comments')">Comments</button></li>
            <li><button onclick="showSection('moderate-users')">Users</button></li>
            <li><button onclick="showSection('moderate-hashtags')">Hashtags</button></li>
            <li><button onclick="showSection('moderate-reports')">Reports</button></li>
            <li><button onclick="showSection('moderation-logs')">Logs</button></li>
        </ul>
    </div>

    <!-- Moderation Sections -->
    <div class="moderation-sections">

        <!-- Moderate Posts -->
        <div id="moderate-posts" class="moderation-section" style="display: none;">
            <h2>Posts Moderation</h2>
            <div class="search-bar">
                <input type="text" id="post-search" placeholder="Search posts..." onkeyup="filterModeration('post')">
            </div>
            <?php
            if ($posts->num_rows > 0): 
                while ($post = $posts->fetch_assoc()): ?>
                    <div class="moderation-item" id="post-item-<?= $post['id'] ?>">
                        <p><strong>User:</strong> <?= htmlspecialchars($post['username']) ?></p>
                        <p><strong>Content:</strong> <?= htmlspecialchars($post['content']) ?></p>
                        <p><strong>Created At:</strong> <?= $post['created_at'] ?></p>
                        <button onclick="deletePost(<?= $post['id'] ?>)">Delete</button>
                        <button onclick="flagPost(<?= $post['id'] ?>)">Flag</button>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No posts found.</p>
            <?php endif; ?>
        </div>

   <!-- Moderate Reports -->
   <div id="moderate-reports" class="moderation-section" style="display: block;">
            <h2>Reported Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Reported By</th>
                        <th>Item Type</th>
                        <th>Item ID</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Action Taken</th>
                        <th>Report Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($reports->num_rows > 0): ?>
                        <?php while ($report = $reports->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($report['reporter_username']) ?></td>
                                <td><?= htmlspecialchars($report['item_type']) ?></td>
                                <td><?= htmlspecialchars($report['item_id']) ?></td>
                                <td><?= htmlspecialchars($report['reason']) ?></td>
                                <td class="<?= strtolower($report['status']) ?>">
                                    <?= htmlspecialchars($report['status']) ?>
                                </td>
                                <td><?= htmlspecialchars($report['action_taken']) ?></td>
                                <td><?= $report['created_at'] ?></td>
                                <td>
                                    <?php if ($report['status'] == 'Pending'): ?>
                                        <button onclick="resolveReport(<?= $report['id'] ?>)">Resolve</button>
                                        <button onclick="dismissReport(<?= $report['id'] ?>)">Dismiss</button>
                                    <?php endif; ?>
                                    <button onclick="viewReportDetails(<?= $report['id'] ?>)">View Details</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No reports found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <!-- Moderate Comments -->
        <div id="moderate-comments" class="moderation-section" style="display: none;">
            <h2>Comments Moderation</h2>
            <div class="search-bar">
                <input type="text" id="comment-search" placeholder="Search comments..." onkeyup="filterModeration('comment')">
            </div>
            <?php
            if ($comments->num_rows > 0): 
                while ($comment = $comments->fetch_assoc()): ?>
                    <div class="moderation-item" id="comment-item-<?= $comment['id'] ?>">
                        <p><strong>User:</strong> <?= htmlspecialchars($comment['username']) ?></p>
                        <p><strong>Comment:</strong> <?= htmlspecialchars($comment['content']) ?></p>
                        <button onclick="deleteComment(<?= $comment['id'] ?>)">Delete</button>
                        <button onclick="flagComment(<?= $comment['id'] ?>)">Flag</button>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No comments found.</p>
            <?php endif; ?>
        </div>

        <!-- Moderate Users -->
        <div id="moderate-users" class="moderation-section" style="display: none;">
            <h2>User Moderation</h2>
            <div class="search-bar">
                <input type="text" id="user-search" placeholder="Search users..." onkeyup="filterModeration('user')">
            </div>
            <?php
            if ($users->num_rows > 0): 
                while ($user = $users->fetch_assoc()): ?>
                    <div class="moderation-item" id="user-item-<?= $user['id'] ?>">
                        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                        <button onclick="banUser(<?= $user['id'] ?>)">Ban</button>
                        <button onclick="unbanUser(<?= $user['id'] ?>)">Unban</button>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>

        <!-- Moderate Hashtags -->
        <div id="moderate-hashtags" class="moderation-section" style="display: none;">
            <h2>Hashtag Moderation</h2>
            <div class="search-bar">
                <input type="text" id="hashtag-search" placeholder="Search hashtags..." onkeyup="filterModeration('hashtag')">
            </div>
            <?php
            if ($hashtags->num_rows > 0): 
                while ($hashtag = $hashtags->fetch_assoc()): ?>
                    <div class="moderation-item" id="hashtag-item-<?= $hashtag['id'] ?>">
                        <p><strong>Hashtag:</strong> <?= htmlspecialchars($hashtag['hashtag']) ?></p>
                        <button onclick="removeHashtag(<?= $hashtag['id'] ?>)">Remove</button>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No hashtags found.</p>
            <?php endif; ?>
        </div>

        <!-- Moderate Reports -->
        <div id="moderate-reports" class="moderation-section" style="display: none;">
            <h2>Reported Items</h2>
            <?php
            if ($reports->num_rows > 0): 
                while ($report = $reports->fetch_assoc()): ?>
                    <div class="moderation-item" id="report-item-<?= $report['id'] ?>">
                        <p><strong>Reported By:</strong> <?= htmlspecialchars($report['reporter_username']) ?></p>
                        <p><strong>Item:</strong> <?= htmlspecialchars($report['item_type']) ?> (ID: <?= htmlspecialchars($report['item_id']) ?>)</p>
                        <p><strong>Reason:</strong> <?= htmlspecialchars($report['reason']) ?></p>
                        <button onclick="resolveReport(<?= $report['id'] ?>)">Resolve</button>
                        <button onclick="dismissReport(<?= $report['id'] ?>)">Dismiss</button>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No reports found.</p>
            <?php endif; ?>
        </div>

        <!-- Moderation Logs -->
        <div id="moderation-logs" class="moderation-section" style="display: none;">
            <h2>Moderation Logs</h2>
            <?php
            if ($logs->num_rows > 0): 
                while ($log = $logs->fetch_assoc()): ?>
                    <div class="log-item">
                        <p><strong>Action:</strong> <?= htmlspecialchars($log['action']) ?></p>
                        <p><strong>Performed By:</strong> <?= htmlspecialchars($log['moderator']) ?></p>
                        <p><strong>Timestamp:</strong> <?= htmlspecialchars($log['timestamp']) ?></p>
                    </div>
                <?php endwhile; 
            else: ?>
                <p>No logs found.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script src="js/navbar.js"></script>
<script>
// Add JavaScript for filtering and moderation actions

// Show section for moderation categories
function showSection(sectionId) {
    const sections = document.querySelectorAll('.moderation-section');
    sections.forEach(section => section.style.display = 'none');
    document.getElementById(sectionId).style.display = 'block';
}

// Filter moderation items
function filterModeration(type) {
    const searchInput = document.getElementById(type + '-search');
    const items = document.querySelectorAll(`#${type}-item`);
    const query = searchInput.value.toLowerCase();

    items.forEach(item => {
        const content = item.innerText.toLowerCase();
        if (content.includes(query)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Example functions for moderation actions
function deletePost(postId) {
    alert('Post ' + postId + ' deleted');
}

function flagPost(postId) {
    alert('Post ' + postId + ' flagged');
}

function deleteComment(commentId) {
    alert('Comment ' + commentId + ' deleted');
}

function flagComment(commentId) {
    alert('Comment ' + commentId + ' flagged');
}

function banUser(userId) {
    alert('User ' + userId + ' banned');
}

function unbanUser(userId) {
    alert('User ' + userId + ' unbanned');
}

function removeHashtag(hashtagId) {
    alert('Hashtag ' + hashtagId + ' removed');
}

function resolveReport(reportId) {
    alert('Report ' + reportId + ' resolved');
}

function dismissReport(reportId) {
    alert('Report ' + reportId + ' dismissed');
}
</script>
</body>
</html>
