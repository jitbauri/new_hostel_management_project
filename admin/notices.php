<?php
session_start();
require_once __DIR__ . '/../include/dbConnect.php';

if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$success = "";
$error = "";

// Add notice
if (isset($_POST['add_notice'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO notices (title, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $message);
    if ($stmt->execute()) {
        $success = "Notice added successfully!";
    } else {
        $error = "Error adding notice: " . $stmt->error;
    }
    $stmt->close();
}

// Delete notice
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM notices WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Notice deleted successfully!";
    } else {
        $error = "Failed to delete notice: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch notices
$notices = $conn->query("SELECT * FROM notices ORDER BY date_posted DESC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notices</title>
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
            padding: 0;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            color: var(--dark);
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light);
        }
        
        h3 {
            color: var(--primary);
            margin-top: 30px;
        }
        
        form {
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        form input, form textarea {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        form input:focus, form textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        form textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: var(--secondary);
        }
        
        .notice-item {
            border-left: 4px solid var(--primary);
            margin: 20px 0;
            padding: 15px;
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        
        .notice-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .notice-item strong {
            color: var(--dark);
            font-size: 18px;
        }
        
        .notice-item p {
            margin: 10px 0;
            color: #555;
            line-height: 1.6;
        }
        
        .notice-date {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }
        
        .delete-link {
            color: var(--danger);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .delete-link:hover {
            color: #c0392b;
            text-decoration: underline;
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
        
        @media (max-width: 600px) {
            .container {
                padding: 15px;
                margin: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Manage Notices</h2>

    <?php if ($success): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="title" placeholder="Notice Title" required>
        <textarea name="message" placeholder="Notice message (you can use HTML formatting)" required></textarea>
        <button type="submit" name="add_notice">Post Notice</button>
    </form>

    <h3>All Notices</h3>
    <?php if ($notices->num_rows > 0): ?>
        <?php while ($row = $notices->fetch_assoc()): ?>
            <div class="notice-item">
                <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                <span class="notice-date">Posted on: <?php echo date('M j, Y g:i A', strtotime($row['date_posted'])); ?></span>
                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                <a href="?delete=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this notice?')">Delete Notice</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="notice-item">
            <p>No notices have been posted yet.</p>
            <p>This Features is Complety Not Ready . This Features Will Be Coming Soon......... </p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>