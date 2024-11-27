<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet"  href="CSS/nav.css">

</head>
<style>    
        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            background-color: #f1f1f1;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .pagination .active {
            background-color: #4CAF50;
            color: white;
        }
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
    
    <!-- Main container with navigation -->
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
                <div class="option2 nav-option ">
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
            <a href="hashtags.php">
                <div class="nav-option">
                    <img src="images/hashtag.png" class="nav-img" alt="Profile">
                    <h3>Hashtags</h3>
                </div>
            </a>
            <a href="reported.php">
                <div class="nav-option option1">
                    <img src="images/reporting.png" class="nav-img" alt="Profile">
                    <h3>Report</h3>
                </div>
            </a>
            <a href="Profile.php">
                <div class="nav-option ">
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



<script src="js/navbar.js"></script>
 <!-- Reported Users List and Search -->
<div class="reported-users-container">
    <h2>Reported Users</h2>
    
    <!-- Search form -->
    <form method="GET" action="reported.php">
        <input type="text" name="search" placeholder="Search by username" value="<?= htmlspecialchars($search) ?>" />
        <button type="submit">Search</button>
    </form>

    <!-- Display reported users -->
    <?php if (!empty($reported_users)): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Reported By</th>
                    <th>Reason</th>
                    <th>Report Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reported_users as $user): ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($user['user_image'] ?? 'images/default-avatar.png') ?>" alt="User Avatar" class="avatar-image">
                            <?= htmlspecialchars($user['username']) ?>
                        </td>
                        <td><?= htmlspecialchars($user['reported_by']) ?></td>
                        <td><?= htmlspecialchars($user['reason']) ?></td>
                        <td><?= date('F j, Y, g:i a', strtotime($user['reported_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1&search=<?= urlencode($search) ?>">First</a>
                <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>">Previous</a>
            <?php endif; ?>
            
            <span class="active"><?= $page ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Next</a>
                <a href="?page=<?= $total_pages ?>&search=<?= urlencode($search) ?>">Last</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No reports found.</p>
    <?php endif; ?>
</div>



</body>
</html>
