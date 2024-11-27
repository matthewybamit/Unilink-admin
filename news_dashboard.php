<?php
include 'db_connect.php'; // Include database connection

// Fetch all news items
$stmt = $pdo->prepare("SELECT * FROM news ORDER BY date_published DESC");
$stmt->execute();
$newsItems = $stmt->fetchAll();
?>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: Admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet"  href="CSS/nav.css">
    <link rel="stylesheet"  href="CSS/news.css">
</head>
<body>
    
    <!-- Header section -->
   
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
                <div class="option2 nav-option option1">
                    <img src="Images/Event.png" class="nav-img" alt="Products">
                    <h3>News</h3>
                </div>
            </a>
            <a href="Posting.php">
                <div class="nav-option option3">
                    <img src="images/Posting.png" class="nav-img" alt="Inventory">
                    <h3>Posting</h3>
                </div>
            </a>
            <a href="Moderate.php">
                <div class="nav-option option4">
                    <img src="images/moderator.png" class="nav-img" alt="Schedule">
                    <h3>Moderations</h3>
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
</div>

<!-- Add/Edit/Delete News Section -->
<div class="news-grid">
    <?php foreach ($newsItems as $news): ?>
    <div class="news-card" onclick="openEditModal(<?= $news['news_id'] ?>)">
        <img src="<?= $news['image_url'] ?>" alt="<?= htmlspecialchars($news['title']) ?>">
        <div class="news-content">
            <h3><?= htmlspecialchars($news['title']) ?></h3>
            <p><?= substr($news['content'], 0, 100) ?>...</p>
        </div>
        <div class="actions">
            <button onclick="editNews(<?= $news['news_id'] ?>)">Edit</button>
            <button onclick="deleteNews(<?= $news['news_id'] ?>)">Delete</button>
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Add News Button -->
    <div class="add-news" onclick="openAddModal()">
        <span>+</span>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="newsModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Add News</h2>
        <form id="newsForm" action="submit_news.php" method="POST">
            <input type="hidden" name="news_id" id="newsId">
            <input type="text" name="title" id="newsTitle" placeholder="News Title" required>
            <textarea name="content" id="newsContent" placeholder="News Content" rows="4" required></textarea>
            <input type="text" name="author" id="newsAuthor" placeholder="Author" required>   
            <!-- Added File Input for Image Upload -->
            <input type="file" name="image_file" id="newsImageFile" accept="image/*">
            <button type="submit">Save</button>
        </form>
    </div>
</div>


<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add News';
        document.getElementById('newsForm').reset();
        document.getElementById('newsModal').style.display = 'flex';
    }

    function openEditModal(newsId) {
        // Fetch existing news data using Ajax and populate the form
        // For now, we'll just simulate by setting the news_id to the form
        document.getElementById('modalTitle').innerText = 'Edit News';
        document.getElementById('newsId').value = newsId;
        document.getElementById('newsModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('newsModal').style.display = 'none';
    }

    function deleteNews(newsId) {
        if (confirm('Are you sure you want to delete this news?')) {
            window.location.href = `delete_news.php?id=${newsId}`;
        }
    }

    window.onclick = function(event) {
        const modal = document.getElementById('newsModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
</script>
    <script src="js/navbar.js"></script>

</body>
</html>
