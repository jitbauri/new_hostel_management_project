<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once __DIR__ . '/../include/dbConnect.php';

// Initialize variables
$success = '';
$error = '';

// Handle Add Admin
if (isset($_POST['add_admin'])) {
    $name = trim($_POST['name']);
    $college_id = trim($_POST['college_id']);
    $password = trim($_POST['password']);
    $photo = 'default_admin.jpg'; // Default photo

    // Handle file upload if provided
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/admins/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate secure filename
        $fileExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = $college_id . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
        $targetPath = $uploadDir . $photo;
        
        // Validate image
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileExt), $validExtensions)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
            $error = "Failed to upload photo.";
        }
    }

    if (empty($error)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO admins (name, college_id, password, photo) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("ssss", $name, $college_id, $hashedPassword, $photo);
            if ($stmt->execute()) {
                $success = "Admin added successfully!";
            } else {
                $error = "Failed to add admin: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle Remove Admin
if (isset($_POST['remove_admin'])) {
    $college_id = trim($_POST['college_id']);

    if (empty($college_id)) {
        $error = "College ID is required!";
    } else {
        // First get photo to delete it
        $stmt = $conn->prepare("SELECT photo FROM admins WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();
        
        // Then delete admin
        $stmt = $conn->prepare("DELETE FROM admins WHERE college_id = ?");
        if ($stmt === false) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("s", $college_id);
            if ($stmt->execute()) {
                $success = "Admin removed successfully!";
                // Delete photo if not default
                if ($admin && $admin['photo'] !== 'default_admin.jpg') {
                    $photoPath = '../uploads/admins/' . $admin['photo'];
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                }
            } else {
                $error = "Failed to remove admin: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Get all current admins
$admins = [];
$result = $conn->query("SELECT name, college_id, photo FROM admins ORDER BY name");
if ($result === false) {
    $error = "Error fetching admin list: " . $conn->error;
} elseif ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $admins[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Management</title>
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2980b9;
            --success: #2ecc71;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --gray: #95a5a6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--dark);
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--light);
            padding-bottom: 10px;
        }

        .form-section {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        input,
        button,
        textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: var(--secondary);
        }

        .admin-list {
            margin-top: 30px;
        }

        .admin-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
            gap: 15px;
        }

        .admin-item:hover {
            background-color: #f9f9f9;
        }

        .photo-container {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
            border-radius: 50%;
            overflow: hidden;
            background: #eee;
        }

        .admin-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .admin-info {
            flex-grow: 1;
        }

        .admin-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .admin-id {
            color: var(--gray);
            font-size: 14px;
        }

        .success {
            color: var(--success);
            background: rgba(46, 204, 113, 0.1);
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid var(--success);
            transition: opacity 0.5s ease;
        }

        .error {
            color: var(--danger);
            background: rgba(231, 76, 60, 0.1);
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
            transition: opacity 0.5s ease;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .admin-item {
                flex-direction: column;
                text-align: center;
            }

            .photo-container {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Administrators</h1>

        <!-- Messages Section -->
        <?php if ($success): ?>
        <div class="success" id="success-message">
            <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="error" id="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <div class="form-section">
            <h2>Add New Admin</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="text" name="college_id" placeholder="College ID" required>
                <input type="password" name="password" placeholder="Password" required minlength="6">
                <label for="photo" style="display: block; margin: 10px 0 5px;">Profile Photo (optional):</label>
                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/gif">
                <button type="submit" name="add_admin">Add Admin</button>
            </form>
        </div>

        <div class="form-section">
            <h2>Remove Admin</h2>
            <form method="POST">
                <input type="text" name="college_id" placeholder="Enter College ID to Remove" required>
                <button type="submit" name="remove_admin" style="background-color: var(--danger);">
                    Remove Admin
                </button>
            </form>
        </div>

        <div class="admin-list">
            <h2>Current Administrators</h2>
            <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $admin): ?>
                <div class="admin-item">
                    <div class="photo-container">
                        <img 
                            src="../uploads/admins/<?php echo !empty($admin['photo']) ? htmlspecialchars($admin['photo']) : 'default_admin.jpg'; ?>" 
                            alt="Admin Photo" 
                            class="admin-photo" 
                            loading="lazy"
                            onerror="this.onerror=null;this.src='../uploads/admins/default_admin.jpg'"
                        >
                    </div>
                    <div class="admin-info">
                        <div class="admin-name"><?php echo htmlspecialchars($admin['name']); ?></div>
                        <div class="admin-id">ID: <?php echo htmlspecialchars($admin['college_id']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="admin-item">
                    <div class="admin-info">No administrators found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-hide messages after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Message handling
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            
            [successMsg, errorMsg].forEach(msg => {
                if (msg) {
                    setTimeout(() => {
                        msg.style.opacity = '0';
                        setTimeout(() => msg.style.display = 'none', 500);
                    }, 3000); // Changed from 10000 to 3000 (3 seconds)
                }
            });

            // Image loading
            document.querySelectorAll('.admin-photo:not(.loaded)').forEach(photo => {
                const img = new Image();
                img.src = photo.src;
                img.onload = img.onerror = () => {
                    photo.classList.add('loaded');
                    if (img.src !== photo.src) {
                        photo.src = '../uploads/admins/default_admin.jpg';
                    }
                };
            });
        });
    </script>
</body>
</html>