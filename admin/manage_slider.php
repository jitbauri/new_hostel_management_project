<?php
session_start();
require_once('../include/dbConnect.php');

// Check admin authentication
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}
$success = "";
$error = "";

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['slider_image'])) {
    try {
        $targetDir = "../uploads/slider/";
        
        // Create directory if it doesn't exist
        if (!file_exists($targetDir) && !mkdir($targetDir, 0755, true)) {
            throw new Exception("Failed to create upload directory");
        }
        
        $fileName = basename($_FILES['slider_image']['name']);
        $targetPath = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        
        // Validate image file
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $validExtensions)) {
            throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed");
        }
        
        if ($_FILES['slider_image']['size'] > 2000000) { // 2MB limit
            throw new Exception("File size must be less than 2MB");
        }
        
        if (!move_uploaded_file($_FILES['slider_image']['tmp_name'], $targetPath)) {
            throw new Exception("Failed to move uploaded file");
        }
        
        $stmt = $conn->prepare("INSERT INTO slider_images (image_path, uploaded_at) VALUES (?, NOW())");
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("s", $fileName);
        if (!$stmt->execute()) {
            throw new Exception("Database insert failed: " . $stmt->error);
        }
        
        $success = "Image uploaded successfully!";
        $stmt->close();
        
    } catch (Exception $e) {
        $error = $e->getMessage();
        // Delete the file if it was uploaded but database failed
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        if ($id <= 0) {
            throw new Exception("Invalid image ID");
        }

        $result = $conn->query("SELECT image_path FROM slider_images WHERE id = $id");
        if (!$result) {
            throw new Exception("Database query failed: " . $conn->error);
        }

        if ($row = $result->fetch_assoc()) {
            $imagePath = "../uploads/slider/" . $row['image_path'];
            
            // Delete file from server
            if (file_exists($imagePath) && !unlink($imagePath)) {
                throw new Exception("Failed to delete image file");
            }

            // Delete from database
            if (!$conn->query("DELETE FROM slider_images WHERE id = $id")) {
                throw new Exception("Database delete failed: " . $conn->error);
            }
            
            $success = "Image deleted successfully!";
        } else {
            throw new Exception("Image not found");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all slider images
$images = $conn->query("SELECT * FROM slider_images ORDER BY uploaded_at DESC");
if (!$images) {
    $error = "Failed to fetch images: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Slider Images</title>
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2980b9;
            --success: #2ecc71;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --border: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: var(--dark);
            margin-bottom: 20px;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        form {
            margin-bottom: 30px;
            padding: 20px;
            background: var(--light);
            border-radius: 8px;
            border: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 15px;
        }

        input[type="file"] {
            display: block;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 4px;
            width: 100%;
            max-width: 400px;
        }

        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--secondary);
        }

        .file-requirements {
            color: #6c757d;
            font-size: 14px;
            margin-top: 10px;
        }

        .slider-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .slider-item {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            background: white;
        }

        .slider-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .slider-item img {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .image-meta {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 0 0 4px 4px;
        }

        .image-meta p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .delete-btn {
            display: inline-block;
            padding: 5px 10px;
            background: var(--danger);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 5px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .success {
            color: var(--success);
            background: rgba(46, 204, 113, 0.1);
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid var(--success);
        }

        .error {
            color: var(--danger);
            background: rgba(231, 76, 60, 0.1);
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
        }

        .no-images {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .slider-gallery {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }
            
            .slider-gallery {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="admindashboard.php" class="back-btn">&larr; Back to Dashboard</a>
        <h2>Slider Image Management</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <input type="file" name="slider_image" accept="image/*" required>
            </div>
            <button type="submit" name="upload_btn">Upload Image</button>
            <p class="file-requirements">Allowed formats: JPG, JPEG, PNG, GIF. Max size: 2MB.</p>
        </form>

        <h3>Current Slider Images</h3>
        <div class="slider-gallery">
            <?php if ($images && $images->num_rows > 0): ?>
                <?php while ($row = $images->fetch_assoc()): ?>
                    <div class="slider-item">
                        <img src="../uploads/slider/<?php echo htmlspecialchars($row['image_path']); ?>" 
                             alt="Slider Image <?php echo htmlspecialchars($row['id']); ?>">
                        <div class="image-meta">
                            <p>Uploaded: <?php echo date('M j, Y g:i A', strtotime($row['uploaded_at'])); ?></p>
                            <a href="?delete=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this image?');"
                               class="delete-btn">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-images">
                    <p>No slider images found. Upload some images to display on the homepage.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-hide messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.success, .error');
            messages.forEach(msg => {
                setTimeout(() => {
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }, 5000);
            });
            
            // Prevent form resubmission on page refresh
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        });
    </script>
</body>
</html>