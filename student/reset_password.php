<?php
session_start();
require_once('../include/dbConnect.php');

// Verify user is logged in
if (!isset($_SESSION['college_id'])) {
    header("Location: signin.php");
    exit();
}

$college_id = $_SESSION['college_id'];
$errmsg = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM students WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $student = $result->fetch_assoc();
        $stored_password = $student['password'];
        
        // Check if password matches plain text (transition phase)
        if ($current_password === $stored_password) {
            // Password is in plain text - we should hash it
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE students SET password = ? WHERE college_id = ?");
                $update_stmt->bind_param("ss", $hashed_password, $college_id);
                
                if ($update_stmt->execute()) {
                    $success = "Password updated successfully!";
                } else {
                    $errmsg = "Error updating password: " . $conn->error;
                }
            } else {
                $errmsg = "New passwords do not match!";
            }
        }
        // Check if password matches hash
        elseif (password_verify($current_password, $stored_password)) {
            if ($new_password === $confirm_password) {
                // Update password (even if same, we'll re-hash it)
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE students SET password = ? WHERE college_id = ?");
                $update_stmt->bind_param("ss", $hashed_password, $college_id);
                
                if ($update_stmt->execute()) {
                    $success = "Password updated successfully!";
                } else {
                    $errmsg = "Error updating password: " . $conn->error;
                }
            } else {
                $errmsg = "New passwords do not match!";
            }
        } else {
            $errmsg = "Current password is incorrect!";
        }
    } else {
        $errmsg = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      margin: 0;
      padding: 20px;
    }
    
    .password-container {
      max-width: 500px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }
    
    input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }
    
    .btn {
      width: 100%;
      padding: 14px;
      background-color: #01BF7E;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    
    .btn:hover {
      background-color: #00a76d;
    }
    
    .message {
      padding: 12px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
    
    .error {
      background-color: rgba(231, 76, 60, 0.1);
      color: #e74c3c;
      border: 1px solid rgba(231, 76, 60, 0.3);
    }
    
    .success {
      background-color: rgba(39, 174, 96, 0.1);
      color: #27ae60;
      border: 1px solid rgba(39, 174, 96, 0.3);
    }
  </style>
</head>
<body>
  <div class="password-container">
    <h2>Change Password</h2>
    
    <?php if ($errmsg): ?>
      <div class="message error"><?php echo htmlspecialchars($errmsg); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="message success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form method="post" action="">
      <div class="form-group">
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>
      </div>
      
      <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required>
      </div>
      
      <div class="form-group">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
      </div>
      
      <button type="submit" class="btn">Change Password</button>
    </form>
  </div>
</body>
</html>