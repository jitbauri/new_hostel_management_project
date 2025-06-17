<?php
session_start();
require_once('../include/dbConnect.php');

if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_college_id'];
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Fetch current admin password
    $stmt = $conn->prepare("SELECT password FROM admins WHERE college_id = ?");
    $stmt->bind_param("s", $admin_id);
    $stmt->execute();
    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if ($current !== $stored_password) {
        $error = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $error = "New password and confirm password do not match.";
    } else {
        // Update admin password
        $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE college_id = ?");
        $stmt->bind_param("ss", $new, $admin_id);

        if ($stmt->execute()) {
            // ✅ Automatically update all student passwords too
            $update_students = $conn->prepare("UPDATE students SET password = ?");
            $update_students->bind_param("s", $new);
            $update_students->execute();
            $update_students->close();

            $success = "Password changed successfully. All student passwords also updated.";
        } else {
            $error = "Failed to update password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Change Password</title>
  <link rel="stylesheet" href="css1/change_password.css">
</head>
<body>
<div class="change-password-box">
    <h2>Change Password</h2>
    <?php if ($success): ?><p class="success"><?php echo $success; ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>

    <form method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
        <button type="submit">Change Password</button>
    </form>
</div>
</body>
</html>
