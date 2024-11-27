<!DOCTYPE html>
<html lang="en">
<head>
    <title>Glassmorphism Login Form</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
    <link href="CSS/login.css" rel="stylesheet" />
</head>
<body>

    <div class="background"></div>
    <div class="form2">
        <img class="logo" src="Images/unilink_logo(2).png" alt="Unilink Logo" />
    </div>

    <!-- Login Form -->
    <form action="login.php" method="post" class="login-form">
        <h3>ADMIN LOGIN</h3>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required />
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required />
        </div>
        <button type="submit">Log In</button>

        <!-- Display error message -->
        <div class="error-message">
            <?php if (isset($_GET['error'])) {
                echo '<span class="text-danger">' . $_GET['error'] . '</span>';
            } ?>
        </div>
    </form>

    <div class="copyright">
        <span class="left">© 2024 Unilink. All rights reserved. Privacy policy</span>
        <span class="right">Terms and conditions test</span>
    </div>

</body>
</html>
