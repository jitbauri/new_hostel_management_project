<?php
session_start();

// Cache control headers - ADD THESE LINES
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// Check authentication
if (!isset($_SESSION['college_id'])) {
    header("Location: signin.php");
    exit();
}
require_once('../include/dbConnect.php');

// Check for college_id session variable (from login)
if (!isset($_SESSION['college_id'])) {
    header("Location: ../studentlogin.php");
    exit();
}

$college_id = $_SESSION['college_id'];

// Fetch student data using prepared statement
$stmt = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
$stmt->bind_param("s", $college_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $student = $result->fetch_assoc();
    $student_id = $student['college_id'];
} else {
    echo "Student data not found.";
    exit();
}

// Fetch total unpaid due
$due_amount = 0;
$due_stmt = $conn->prepare("SELECT SUM(amount) as total_due FROM dues WHERE college_id = ? AND is_paid = 0");
$due_stmt->bind_param("i", $student_id);
$due_stmt->execute();
$due_result = $due_stmt->get_result();

if ($due_row = $due_result->fetch_assoc()) {
    $due_amount = $due_row['total_due'] ?? 0;
}

// meal status

$query = "SELECT status, updated_at FROM meal_status 
          WHERE college_id = ? 
          ORDER BY updated_at DESC LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $college_id);
$stmt->execute();
$result = $stmt->get_result();
$meal_status = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="css3/student_dash.css">
</head>
<body>
<div class="sidebar">
  <h2>Dashboard</h2>
  <ul>
    <li><a href="#">Dashboard</a></li>
    <li><a href="add_visitor.php">Add Visitor</a></li>
    <li><a href="complain.php">Register Complain</a></li>
    <li><a href="givesuggestion.php">Give Suggestion</a></li>
    <li><a href="meal_on_off.php">Meal On/Off</a></li>
    <li><a href="mealmenu.php">Meal Weekly Menu</a></li>
    <li><a href="reset_password.php">Change Password</a></li>
    <li><a href="stlogout.php" style=" color:white; font-weight:bold;">Logout</a></li>
  </ul>
</div>

<div class="main">
  <div class="top-bar">
    <div class="date">Date: <?php echo date('d M Y'); ?></div>
    <div class="profile"> 
      <img src="../uploads/students/<?php echo $student['photo']; ?>" alt="Profile" class="profile-pic">
    </div>
  </div>

<div class="student-card">
  <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?></h2>

  <table class="student-info-table">
    <tr>
      <th>College ID</th>
      <td><?php echo htmlspecialchars($student['college_id']); ?></td>
    </tr>
    <tr>
      <th>University ID</th>
      <td><?php echo htmlspecialchars($student['university_id']); ?></td>
    </tr>
    <tr>
      <th>Room Number</th>
      <td><?php echo htmlspecialchars($student['room_number']); ?></td>
    </tr>
    <tr>
      <th>Phone</th>
      <td><?php echo htmlspecialchars($student['mobile_number']); ?></td>
    </tr>
    <tr>
      <th>Guardian Phone</th>
      <td><?php echo htmlspecialchars($student['guardian_mobile_number']); ?></td>
    </tr>
    <tr>
      <th>Course</th>
      <td><?php echo htmlspecialchars($student['course']); ?></td>
    </tr>
    <tr>
      <th>Meal Status</th>
      <td>
        <?php
        if ($meal_status) {
            echo htmlspecialchars(ucfirst($meal_status['status'])) .
                " (" . date('d M Y', strtotime($meal_status['updated_at'])) . ")";
        } else {
            echo "Not submitted yet";
        }
        ?>
      </td>
    </tr>
    <tr>
      <th style="color: #e74c3c;">Due Amount</th>
      <td style="color: #e74c3c; font-weight: bold;">₹<?php echo number_format($due_amount, 2); ?></td>
    </tr>
  </table>
</div>

<script>
// Prevent back-button after logout
window.history.pushState(null, null, document.URL);
window.addEventListener('popstate', function() {
    window.history.pushState(null, null, document.URL);
});
</script>
</body>
</html>