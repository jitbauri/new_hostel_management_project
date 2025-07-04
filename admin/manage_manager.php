<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once('../include/dbConnect.php');

$success = "";
$error = "";
$student = null;
$manager = null;
$managers = [];

// Handle manager addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_manager'])) {
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];
    $active = isset($_POST['active']) ? 1 : 0; // Convert checkbox to TINYINT (1 or 0)

    // Verify student exists first and get their password
    $stmt = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $student_result = $stmt->get_result();
    
    if ($student_result->num_rows === 0) {
        $error = "No student found with this College ID.";
    } else {
        $student_data = $student_result->fetch_assoc();
        $password = $student_data['password']; // Use student's existing password
        
        // Check if manager already exists
        $stmt = $conn->prepare("SELECT * FROM managers WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Manager with this College ID already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO managers (name, college_id, password, active) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $college_id, $password, $active);
            if ($stmt->execute()) {
                $success = "Manager added successfully. They will use their student password to login.";
            } else {
                $error = "Error adding manager: " . $conn->error;
            }
        }
    }
    $stmt->close();
}

// Handle search for student or manager
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search_term = isset($_POST['search_term']) ? trim($_POST['search_term']) : '';
    
    if (!empty($search_term)) {
        // Search students
        $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR college_id = ?");
        $search_param = "%" . $search_term . "%";
        $stmt->bind_param("ss", $search_param, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            
            // Check if this student is already a manager
            $stmt = $conn->prepare("SELECT * FROM managers WHERE college_id = ?");
            $stmt->bind_param("s", $student['college_id']);
            $stmt->execute();
            $manager_result = $stmt->get_result();
            
            if ($manager_result->num_rows > 0) {
                $manager = $manager_result->fetch_assoc();
            }
        } else {
            $error = "No student found with that name or ID.";
        }
        $stmt->close();
    } else {
        $error = "Please enter a search term.";
    }
}

// Handle manager deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $college_id = $_POST['college_id'];
    $stmt = $conn->prepare("DELETE FROM managers WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    if ($stmt->execute()) {
        $success = "Manager deleted successfully.";
        $manager = null;
    } else {
        $error = "Error deleting manager: " . $conn->error;
    }
    $stmt->close();
}

// Handle manager status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $college_id = $_POST['college_id'];
    $active = isset($_POST['active']) ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE managers SET active = ? WHERE college_id = ?");
    $stmt->bind_param("is", $active, $college_id);
    if ($stmt->execute()) {
        $status = $active ? 'activated' : 'deactivated';
        $success = "Manager status updated successfully. The manager has been $status.";
        
        // Refresh manager data if we're currently viewing this manager
        if ($manager && $manager['college_id'] == $college_id) {
            $manager['active'] = $active;
        }
    } else {
        $error = "Error updating manager status: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all managers
$result = $conn->query("SELECT * FROM managers ORDER BY active DESC, college_id ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $managers[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Student Managers</title>
    <style>
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .success {
            color: green;
            padding: 10px;
            margin: 10px 0;
            background: #e8f5e9;
        }
        .error {
            color: red;
            padding: 10px;
            margin: 10px 0;
            background: #ffebee;
        }
        form {
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        input, button, select {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
        .info-box {
            background: #e3f2fd;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .manager-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        .manager-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: relative;
        }
        .active-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            color: white;
        }
        .active {
            background: #4CAF50;
        }
        .inactive {
            background: #F44336;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .checkbox-container input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Search Student</h2>
    <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    
    <form method="post">
        <input type="text" name="search_term" placeholder="Enter Student Name or College ID" required>
        <button type="submit" name="search">Search Student</button>
    </form>

    <?php if ($student): ?>
        <div class="info-box">
            <h3>Student Details</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
            <p><strong>College ID:</strong> <?= htmlspecialchars($student['college_id']) ?></p>
            
            <?php if (!$manager): ?>
                <h3>Add as Manager</h3>
                <form method="post">
                    <input type="hidden" name="college_id" value="<?= htmlspecialchars($student['college_id']) ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($student['name']) ?>">
                    <div class="checkbox-container">
                        <input type="checkbox" name="active" id="active" checked>
                        <label for="active">Active</label>
                    </div>
                    <p>This student will use their existing student password to login as manager.</p>
                    <button type="submit" name="add_manager">Add as Manager</button>
                </form>
            <?php else: ?>
                <h3>Manager Status</h3>
                <p>This student is already a manager.</p>
                <form method="post">
                    <input type="hidden" name="college_id" value="<?= htmlspecialchars($manager['college_id']) ?>">
                    <div class="checkbox-container">
                        <input type="checkbox" name="active" id="active" <?= $manager['active'] ? 'checked' : '' ?>>
                        <label for="active">Active</label>
                    </div>
                    <button type="submit" name="update_status">Update Status</button>
                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to remove manager access?');" 
                            style="background: #F44336; margin-top: 10px;">
                        Remove Manager Access
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h2>All Managers</h2>
    <div class="manager-list">
        <?php foreach ($managers as $m): ?>
            <div class="manager-item">
                <span class="active-status <?= $m['active'] ? 'active' : 'inactive' ?>">
                    <?= $m['active'] ? 'Active' : 'Inactive' ?>
                </span>
                <p><strong>Name:</strong> <?= htmlspecialchars($m['name']) ?></p>
                <p><strong>College ID:</strong> <?= htmlspecialchars($m['college_id']) ?></p>
                <p><strong>Status:</strong> <?= $m['active'] ? 'Active' : 'Inactive' ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>