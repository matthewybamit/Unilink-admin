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

// Pagination
$posts_per_page = 5; // Number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Search
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the query with pagination and search
$query = "SELECT * FROM forum_posts WHERE content LIKE ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$search_like = '%' . $search_term . '%';
$stmt->bind_param('sii', $search_like, $posts_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of posts for pagination
$total_query = "SELECT COUNT(*) FROM forum_posts WHERE content LIKE ?";
$total_stmt = $conn->prepare($total_query);
$total_stmt->bind_param('s', $search_like);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_posts = $total_result->fetch_row()[0];
$total_pages = ceil($total_posts / $posts_per_page);
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

/* Moderation Section */
.moderation-section {
    margin-left: 400px;
transform: translateY(5%);

    padding: 20px;
    border-radius: 8px;

    width: 80%;  /* Adjust width as needed */
    max-width: 900px;  /* Set a max-width to prevent it from becoming too wide */
}

.moderation-section h2 {
    font-size: 24px;
    margin-bottom: 15px;
    color: #2c3e50;
}

.search-bar {
    margin-bottom: 20px;
}

.search-bar input {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    outline: none;
}

.search-bar input:focus {
    border-color: #2c3e50;
}

/* Moderation Item */
.moderation-item {
    background-color: #ecf0f1;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.05);
}

.moderation-item p {
    margin: 10px 0;
    font-size: 16px;
}

.moderation-item button {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.moderation-item button:hover {
    background-color: #c0392b;
}

.moderation-item .created-at {
    font-size: 14px;
    color: #95a5a6;
}

.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    display: inline-block;
    padding: 10px 15px;
    margin: 0 5px;
    background-color: #ecf0f1;
    border-radius: 5px;
    text-decoration: none;
    color: #2c3e50;
}

.pagination a:hover {
    background-color: #bdc3c7;
}

.pagination .active {
    background-color: #3498db;
    color: #fff;
}

/* CSS for resizing the post image */
.post-image {
    max-width: 300px; /* Set max width to 300px */
    width: 100%; /* Ensure the image scales within the container */
    height: auto; /* Keep the image's aspect ratio intact */
    border-radius: 8px; /* Optional: adds rounded corners */
    object-fit: cover; /* Optional: makes the image fill the container */
}
</style>
<body>
    <header>
        <div class="logosec">
            <div class="logo">Unilink</div>
            <img src="Images/hamburger.png" class="icn menuicn" id="menuicn" alt="menu-icon">
        </div>
        <div class="message">
            <div class="dp">
                <img src="user-placeholder.png" class="dpicn" alt="User Profile" />
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="navcontainer">
            <nav class="nav">
                <div class="user-account">
                    <a href="Profile.php"><img src="images/user-icon1.png" alt=""></a>
                    <h2>User Profile</h2>
                </div>
                <a href="news_dashboard.php">
                    <div class="option2 nav-option">
                        <img src="Images/Event.png" class="nav-img" alt="News">
                        <h3>News</h3>
                    </div>
                </a>
                <a href="Posting.php">
                    <div class="nav-option option1"> 
                        <img src="images/Posting.png" class="nav-img" alt="Posting">
                        <h3>Posting</h3>
                    </div>
                </a>
                <a href="Moderate.php">
                    <div class="nav-option option4">
                        <img src="images/moderator.png" class="nav-img" alt="Moderations">
                        <h3>Moderations</h3>
                    </div>
                </a>
                <a href="hashtags.php">
                <div class="nav-option">
                    <img src="images/hashtag.png" class="nav-img" alt="Profile">
                    <h3>Hashtags</h3>
                </div>
            </a>
            <a href="reported.php">
                <div class="nav-option">
                    <img src="images/reporting.png" class="nav-img" alt="Profile">
                    <h3>Report</h3>
                </div>
            </a>
                <a href="Profile.php">
                    <div class="nav-option option5">
                        <img src="images/user-icon1.png" class="nav-img" alt="Profile">
                        <h3>Profile</h3>
                    </div>
                </a>
                <div id="logoutButton" class="nav-option logout" onclick="confirmLogout()">
                    <img src="images/logout.png" class="nav-img" alt="Logout" />
                    <h3>Logout</h3>
                </div>
            </nav>
        </div>

        <div id="moderate-posts" class="moderation-section">
            <h2>Posts Moderation</h2>
            <div class="search-bar">
                <form method="GET" action="">
                    <input type="text" id="post-search" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search_term) ?>">
                    <button type="submit" style="display:none;">Search</button>
                </form>
            </div>
            <?php if ($result->num_rows > 0): ?>
    <?php while ($post = $result->fetch_assoc()): ?>
        <div class="moderation-item" id="post-item-<?= $post['id'] ?>">
            <p><strong>User:</strong> <?= htmlspecialchars($post['username']) ?></p>
            <p><strong>Content:</strong> <?= htmlspecialchars($post['content']) ?></p>
            <p><strong>Created At:</strong> <?= $post['created_at'] ?></p>
            
            <!-- Display image if it exists -->
            <?php if (!empty($post['image'])): ?>
                <div>
                    <strong>Image:</strong>
                    <img src="<?= $post['image'] ?>" alt="Post Image" class="post-image"/>
                </div>
            <?php endif; ?>
            
            <button onclick="deletePost(<?= $post['id'] ?>)">Delete</button>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>


            <!-- Pagination Links -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search_term) ?>">Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search_term) ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search_term) ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function deletePost(postId) {
        if (confirm("Are you sure you want to delete this post?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_post.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText === 'success') {
                        document.getElementById("post-item-" + postId).remove();
                        alert("Post deleted successfully!");
                    } else {
                        alert("Error: Could not delete post.");
                    }
                }
            };

            xhr.send("post_id=" + postId);
        }
    }
    </script>

    <script src="js/navbar.js"></script>
</body>
</html>
