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



$users = $conn->query("SELECT * FROM users");



?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/moderation.css">
    <link rel="stylesheet" href="CSS/nav.css">
</head>

<Style> 


</style>

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
                <div class="option2 nav-option">
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
                <div class="nav-option  option1">
                    <img src="images/moderator.png" class="nav-img" alt="Schedule">
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
</div>
<!-- Moderate Users -->
<div id="moderate-users" class="moderation-section" >
    <h2>User Moderation</h2>
    
    <!-- User search bar -->
    <div class="search-bar">
        <input type="text" id="user-search" placeholder="Search users..." onkeyup="filterModeration('user')">
    </div>

    <?php
    if ($users->num_rows > 0): 
        while ($user = $users->fetch_assoc()): ?>
            <div class="moderation-item" id="user-item-<?= $user['id'] ?>" data-user-id="<?= $user['id'] ?>">
                <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Status:</strong> <span id="user-status-<?= $user['id'] ?>"><?= $user['status'] ?></span></p>

                <button onclick="viewUserDetails(<?= $user['id'] ?>)">View Profile</button>
                <button onclick="banUser(<?= $user['id'] ?>)" id="ban-button-<?= $user['id'] ?>">Ban</button>
                <button onclick="unbanUser(<?= $user['id'] ?>)" id="unban-button-<?= $user['id'] ?>" style="display: none;">Unban</button>
            </div>
        <?php endwhile; 
    else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</div>


<script>
// Filter users based on search input
function filterModeration(type) {
    const searchQuery = document.getElementById(`${type}-search`).value.toLowerCase();
    const items = document.querySelectorAll(`#${type}-moderation .moderation-item`);

    items.forEach(item => {
        const username = item.querySelector('p strong').textContent.toLowerCase();
        if (username.includes(searchQuery)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}

// Function to ban a user
function banUser(userId) {
    // Send an AJAX request to ban the user
    fetch('moderate_user.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'ban', userId: userId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById(`user-status-${userId}`).innerText = 'Banned';
            document.getElementById(`ban-button-${userId}`).style.display = 'none';
            document.getElementById(`unban-button-${userId}`).style.display = 'inline-block';
        } else {
            alert('Failed to ban user.');
        }
    });
}

// Function to unban a user
function unbanUser(userId) {
    // Send an AJAX request to unban the user
    fetch('moderate_user.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'unban', userId: userId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById(`user-status-${userId}`).innerText = 'Active';
            document.getElementById(`unban-button-${userId}`).style.display = 'none';
            document.getElementById(`ban-button-${userId}`).style.display = 'inline-block';
        } else {
            alert('Failed to unban user.');
        }
    });
}

// Function to view user details
function viewUserDetails(userId) {
    // Open a modal or redirect to the user profile page to view detailed information
    window.location.href = `user_profile.php?user_id=${userId}`;
}

// Function to reset user password
function resetPassword(userId) {
    // Send an AJAX request to reset the user's password
    fetch('moderate_user.php', {
        method: 'POST',
        body: JSON.stringify({ action: 'reset_password', userId: userId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Password reset successfully.');
        } else {
            alert('Failed to reset password.');
        }
    });
}

</script>
<script src="js/navbar.js"></script>

</body>
</html>
