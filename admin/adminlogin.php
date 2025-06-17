<?php
session_start();

// Cache control headers - ADD THESE LINES
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_college_id'])) {
    header("Location: admindashboard.php");
    exit();
}
$errmsg = "";

// Set base path dynamically
$base_path = dirname($_SERVER['PHP_SELF']);
$login_path = rtrim($base_path, '/') . '/admindashboard.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Use absolute path for include
    require_once __DIR__ . '/../include/dbConnect.php';

    $college_id = $_POST['college_id'];
    $password = $_POST['password'];

    // SECURITY FIX: Use password_verify with hashed passwords
    $stmt = $conn->prepare("SELECT college_id, password FROM admins WHERE college_id = ?");
    $stmt->bind_param("s", $college_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_college_id'] = $college_id;
            // Use absolute URL for redirect
            header("Location: " . $login_path);
            exit();
        }
    }
    
    $errmsg = "* College ID or Password is incorrect";
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="<?php echo $base_path; ?>/css1/adminlogin.css">
  <script>
    function goBack() {
      window.location.href = "<?php echo $base_path; ?>/index.php";
    }
  </script>
</head>
<body>
<div class="center">
  <h1>Admin Login</h1>
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
    <span style="color: red; font-size: 14px;">
      <?php echo htmlspecialchars($errmsg); ?>
    </span>
  </form>
</div>
</body>
</html>