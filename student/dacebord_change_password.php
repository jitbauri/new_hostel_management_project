<?php
session_start();
if (!isset($_SESSION['college_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include '../dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = $_SESSION['college_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT password FROM students WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if ($current_password == $db_password) {
        $stmt = $conn->prepare("UPDATE students SET password = ? WHERE college_id = ?");
        $stmt->bind_param("ss", $new_password, $college_id);
        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully');</script>";
        } else {
            echo "<script>alert('Something went wrong');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
  <link rel="stylesheet" href="../css/change_password.css">
</head>
<body>
<div class="center">
  <h1>Change Password</h1>
  <form method="post" action="change_password.php">
    <div class="txt_field">
      <input type="password" name="current_password" required>
      <label>Current Password</label>
      <span></span>
    </div>
    <div class="txt_field">
      <input type="password" name="new_password" required>
      <label>New Password</label>
      <span></span>
    </div>
    <input type="submit" value="Change Password">
    <div class="back">
      <a href="studentdashboard.php">Back to Dashboard</a>
    </div>
  </form>
</div>
</body>
</html>
