<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once ('../include/dbConnect.php');

$success = "";
$error = "";
$manager = null;
$managers = [];

// Handle manager addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_manager'])) {
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM managers WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Manager with this College ID already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO managers (name, college_id, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $college_id, $password);
        if ($stmt->execute()) {
            $success = "Manager added successfully.";
        } else {
            $error = "Error adding manager.";
        }
        $stmt->close();
    }
}

// Handle search and delete manager
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $college_id = $_POST['college_id'];
        $stmt = $conn->prepare("SELECT * FROM managers WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $manager = $result->fetch_assoc();
        } else {
            $error = "Manager not found.";
        }
        $stmt->close();
    }

    if (isset($_POST['delete'])) {
        $college_id = $_POST['college_id'];
        $stmt = $conn->prepare("DELETE FROM managers WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        if ($stmt->execute()) {
            $success = "Manager deleted successfully.";
            $manager = null;
        } else {
            $error = "Error deleting manager.";
        }
        $stmt->close();
    }
}

// Fetch all managers
$result = $conn->query("SELECT * FROM managers");
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
    <title>Manage Managers</title>
    <link rel="stylesheet" href="css1/manage_manager.css">
</head>
<body>
<div class="container">
    <h2>Add Manager</h2>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <form method="post">
        <input type="text" name="name" placeholder="Manager Name" required>
        <input type="text" name="college_id" placeholder="College ID" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="add_manager">Add Manager</button>
    </form>

    <h2>Search & Delete Manager</h2>
    <form method="post">
        <input type="text" name="college_id" placeholder="Enter College ID to Search" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($manager): ?>
        <div class="manager-info">
            <p><strong>Name:</strong> <?= $manager['name'] ?></p>
            <p><strong>College ID:</strong> <?= $manager['college_id'] ?></p>
            <form method="post">
                <input type="hidden" name="college_id" value="<?= $manager['college_id'] ?>">
                <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this manager?');">Delete Manager</button>
            </form>
        </div>
    <?php endif; ?>

    <h2>All Managers</h2>
    <div class="manager-list">
        <?php foreach ($managers as $m): ?>
            <div class="manager-item">
                <strong>Name:</strong> <?= $m['name'] ?> <br>
                <strong>College ID:</strong> <?= $m['college_id'] ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
