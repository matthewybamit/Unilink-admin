<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet"  href="CSS/nav.css">

</head>
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
                    <img src="Images/Event.png" class="nav-img" alt="Products">
                    <h3>News</h3>
                </div>
            </a>
            <a href="Posting.php">
                <div class="nav-option option1">
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

    <script src="js/navbar.js"></script>

</body>
</html>
