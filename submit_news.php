<?php
include 'db_connect.php'; // Include your DB connection

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_id = isset($_POST['news_id']) ? $_POST['news_id'] : null;
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $image_url = ''; // Default image URL (if no file uploaded)

    // Handle the file upload if there is a new image
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];  // Allowed image types
        $fileType = mime_content_type($_FILES['image_file']['tmp_name']);
        $maxFileSize = 10 * 1024 * 1024;  // Max file size: 10MB

        // Directories for file upload (absolute paths)
        $uploadDir1 = 'C:/xampp/htdocs/Unilink-admin/Images/';  // First directory (admin folder)
        $uploadDir2 = 'C:/xampp/htdocs/Unilink/images/';       // Second directory (main folder)

        // Relative path to store in database
        $relativePath = 'images/';  

        // Validate file type and size
        if (in_array($fileType, $allowedTypes) && $_FILES['image_file']['size'] <= $maxFileSize) {
            $imageName = uniqid() . '_' . basename($_FILES['image_file']['name']);

            // Paths to move the image in both directories
            $imagePath1 = $uploadDir1 . $imageName;
            $imagePath2 = $uploadDir2 . $imageName;

            // Move the uploaded file to the first directory (admin folder)
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $imagePath1)) {
                // Log the path to check if it's correct
                error_log("Image uploaded to first directory: " . $imagePath1);

                // Try to copy the file to the second directory (main folder)
                if (copy($imagePath1, $imagePath2)) {
                    // Log success
                    error_log("Image copied to second directory: " . $imagePath2);
                    // Set the image URL for the database to store the relative path
                    $image_url = $relativePath . $imageName;
                } else {
                    // Log the failure
                    error_log("Failed to copy image to second directory: " . $imagePath2);
                    echo json_encode(['status' => 'error', 'message' => 'Failed to copy the image to the second directory.']);
                    exit;
                }
            } else {
                error_log("Failed to upload image to first directory: " . $imagePath1);
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload the image to the first directory.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid file type or file too large.']);
            exit;
        }
    } else {
        // If no new image, we keep the existing image
        $image_url = $_POST['existing_image_url'];  // Retrieve the existing image URL from the hidden field
    }

    // If news_id is present, update the existing news; otherwise, insert new news
    try {
        if ($news_id) {
            // Update existing news item
            $stmt = $pdo->prepare("UPDATE news SET title = ?, content = ?, author = ?, image_url = ? WHERE news_id = ?");
            $stmt->execute([$title, $content, $author, $image_url, $news_id]);
        } else {
            // Insert new news item
            $stmt = $pdo->prepare("INSERT INTO news (title, content, author, image_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $author, $image_url]);
        }

        // Redirect to news dashboard after success
        header("Location: news_dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error occurred while saving the news item.']);
        exit;
    }
}
?>
