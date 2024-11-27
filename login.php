<?php
session_start();

// Connect to the database
$host = 'localhost';
$db = 'unilink_database';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

// Set charset to avoid potential charset issues
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Check if username and password values are retrieved correctly
    echo "Entered username: $username <br>";
    echo "Entered password: $password <br>";

    // Prepare and bind statement
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // If user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Debugging: Check if the password from the DB matches
        echo "DB stored password (hashed or plain text): $hashed_password <br>";

        // Verify password using password_verify if the password is hashed in the database
        // If the password in the database is stored as plain text, use simple comparison
        if (password_verify($password, $hashed_password)) {
            echo "Password verification successful!<br>";
            $_SESSION['username'] = $username;
            header("Location: news_dashboard.php");
            exit();
        } elseif ($password === $hashed_password) { // If plain text comparison
            echo "Plain text password match successful!<br>";
            $_SESSION['username'] = $username;
            header("Location: news_dashboard.php");
            exit();
        } else {
            echo "Password verification failed!<br>";
            header("Location: Admin_login.php?error=Incorrect password or username");
            exit();
        }
    } else {
        echo "Username not found in the database.<br>";
        header("Location: Admin_login.php?error=Incorrect password or username");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
