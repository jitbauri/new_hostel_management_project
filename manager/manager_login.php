<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// If already logged in
if (isset($_SESSION['manager_id'])) {
    header("Location: manager_dashboard.php");
    exit();
}

$errmsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../include/dbConnect.php');

    $college_id = trim($_POST['college_id']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM managers WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $manager = $result->fetch_assoc();

        if ($password === $manager['password'] || password_verify($password, $manager['password'])) {
            $_SESSION['manager_id'] = $manager['college_id'];
            $_SESSION['manager_name'] = $manager['name'];

            // Upgrade to hashed password if needed
            if ($password === $manager['password']) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE managers SET password = ? WHERE college_id = ?");
                $update_stmt->bind_param("ss", $hashed, $college_id);
                $update_stmt->execute();
            }

            header("Location: manager_dashboard.php");
            exit();
        } else {
            $errmsg = "* College ID or Password is incorrect";
        }
    } else {
        $errmsg = "* College ID or Password is incorrect";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Login</title>
  <link rel="stylesheet" href="css2/manager_login.css">
  <script>
    function goBack() {
      window.location.href = "../index.php";
    }
  </script>
</head>
<body>
<div class="center">
  <h1>Manager Login</h1>
  <form method="post" action="">
    <div class="txt_field">
      <input name="college_id" type="text" required>
      <span></span>
      <label>College ID</label>
    </div>
    <div class="txt_field">
      <input name="password" type="password" required>
      <span></span>
      <label>Password</label>
    </div>
    <input type="button" value="Go Back" onclick="goBack()" class="back">
    <input type="submit" value="Login">
    <div style="height: 30px;"></div>
    <span style="color: red;"><?php echo htmlspecialchars($errmsg); ?></span>
  </form>
</div>
</body>
</html>
