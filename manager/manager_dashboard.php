<?php
session_start();

// Cache control headers - ADD THESE LINES
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// Check authentication
if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}
require_once('../include/dbConnect.php');

$manager_id = $_SESSION['manager_id']; // This is the college_id

// Get manager details
$manager_query = $conn->prepare("SELECT name, college_id FROM managers WHERE college_id = ?");
$manager_query->bind_param("s", $manager_id);
$manager_query->execute();
$manager_result = $manager_query->get_result();

if ($manager_result->num_rows !== 1) {
    session_destroy();
    header("Location: manager_login.php");
    exit();
}

$manager = $manager_result->fetch_assoc();

// Count active meals - FIXED QUERY FOR YOUR TABLE STRUCTURE
$active_meals = 0;
$meal_query = $conn->prepare("SELECT COUNT(*) AS active_meals FROM meal_status WHERE college_id = ? AND status = 'on'");
$meal_query->bind_param("s", $manager_id);
$meal_query->execute();
$meal_result = $meal_query->get_result();

if ($meal_result) {
    $meal_data = $meal_result->fetch_assoc();
    $active_meals = $meal_data['active_meals'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manager Dashboard</title>
  <link rel="stylesheet" href="css2/manager_dashboard.css">
</head>
<body>
<div class="sidebar">
  <h2>Manager Panel</h2>
  <ul>
    <li><a href="manager_dashboard.php">Dashboard</a></li>
    <li><a href="register_student.php">Register Student</a></li>
    <li><a href="view_students.php">View Student Contacts</a></li>
    <li><a href="edit_menu.php">Edit Weekly Menu</a></li>
    <li><a href="manager_dues.php">Manage Dues</a></li>
    <li><a href="#"  onclick=" alert('Cantact the Admin!');">Change Password</a></li>
    <li><a href="manager_logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
  </ul>
</div>

<div class="main">
  <div class="top-bar">
    <span id="datetime"></span>
  </div>

  <div class="dashboard-widgets">
    <div class="card">
      <h3>Total Active Meals</h3>
      <p><?php echo $active_meals; ?></p>
    </div>

    <div class="card">
      <h3>Manager Info</h3>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($manager['name']); ?></p>
      <p><strong>College ID:</strong> <?php echo htmlspecialchars($manager['college_id']); ?></p>
    </div>
  </div>
</div>

<script>
function updateDateTime() {
  const now = new Date();
  const formatted = now.toLocaleString();
  document.getElementById("datetime").textContent = "Date & Time: " + formatted;
}
setInterval(updateDateTime, 1000);
updateDateTime();
</script>
<script>
// Prevent back-button after logout
window.history.pushState(null, null, document.URL);
window.addEventListener('popstate', function() {
    window.history.pushState(null, null, document.URL);
});
</script>
</body>
</html>