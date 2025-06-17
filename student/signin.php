<?php
session_start();

// Cache control headers - ADD THESE LINES
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// If already logged in, redirect to dashboard
if (isset($_SESSION['college_id'])) {
    header("Location: studentdashboard.php");
    exit();
}

$errmsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../include/dbConnect.php');

    $college_id = $_POST['college_id'];
    $password = $_POST['password'];

    // Modified query to select password only
    $stmt = $conn->prepare("SELECT password FROM students WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $student = $result->fetch_assoc();
        $stored_password = $student['password'];
        
        // Check both plain text and hashed password
        if ($password === $stored_password || password_verify($password, $stored_password)) {
            $_SESSION['college_id'] = $college_id;
            
            // If password was plain text, upgrade to hashed
            if ($password === $stored_password) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE students SET password = ? WHERE college_id = ?");
                $update_stmt->bind_param("ss", $hashed_password, $college_id);
                $update_stmt->execute();
            }
            
            header("Location: studentdashboard.php");
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
  <title>Student Login</title>
  <link rel="stylesheet" href="css3/signin.css">
  <script>
    function goBack() {
      window.location.href = "index.php";
    }
  </script>
</head>
<body>

<div class="center">
  <h1>Student Login</h1>
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
    <div class="pass">
      <a href="signin_forgot_password.php">Forgot Password?</a>
    </div>
    <input type="button" value="Go Back" onclick="goBack()" class="back">
    <input type="submit" value="Login">
   <div style="height: 30px;"></div>
    <span style="color: red;"><?php echo $errmsg; ?></span>
  </form>
</div>

</body>
</html>
