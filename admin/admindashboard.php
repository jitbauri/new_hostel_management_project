<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css1/admindashboard.css">
  <link rel="stylesheet" href="css1/loder.css">
</head>
<body>
  <?php
session_start();

// Cache control headers - ADD THESE LINES
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// Check authentication
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// Rest of your dashboard code...
    
    require_once('../include/dbConnect.php');
    
    // Regenerate session ID to prevent session fixation
    if (!isset($_SESSION['admin_last_regenerate'])) {
        session_regenerate_id(true);
        $_SESSION['admin_last_regenerate'] = time();
    } elseif (time() - $_SESSION['admin_last_regenerate'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['admin_last_regenerate'] = time();
    }
    
    
    // Validate college_id format if needed (depending on your ID format)
    $college_id = $_SESSION['admin_college_id'];
    if (!preg_match('/^[a-zA-Z0-9]+$/', $college_id)) {
        session_destroy();
        header("Location: ../admin_login.php?error=invalid_id");
        exit();
    }
    
    // Prepare statement with parameterized query
    $query = $conn->prepare("SELECT name, college_id, photo FROM admins WHERE college_id = ?");
    if (!$query) {
        error_log("Database error: " . $conn->error);
        die("An error occurred. Please try again later.");
    }
    
    $query->bind_param("s", $college_id);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows === 0) {
        session_destroy();
        header("Location: ../admin_login.php?error=invalid_session");
        exit();
    }
    
    $admin = $result->fetch_assoc();
    
    // Sanitize output
    $admin_name = htmlspecialchars($admin['name'], ENT_QUOTES, 'UTF-8');
    $admin_college_id = htmlspecialchars($admin['college_id'], ENT_QUOTES, 'UTF-8');
    $admin_photo = htmlspecialchars($admin['photo'], ENT_QUOTES, 'UTF-8');
    
    // Validate photo path
    $photo_path = '../uploads/' . $admin_photo;
    if (!file_exists($photo_path) || !is_readable($photo_path)) {
        $photo_path = '../uploads/default_admin.jpg'; // Fallback image
    }
  ?>
  <!-- Loader HTML -->
    <div class="loader-wrapper">
        <div class="card">
            <div class="loader">
                <p>loading</p>
                <div class="words">
                    <span class="word">Dashboard</span>
                    <span class="word">Student List</span>
                    <span class="word">Website Control</span>
                    <span class="word">Completed</span>
                </div>
            </div>
        </div>
    </div>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="profile">
     
        <h2><?php echo $admin_name; ?></h2>
        <p>ID: <?php echo $admin_college_id; ?></p>
      </div>
    
      <nav>
        <ul>
          <li><a href="view_all_students.php">View All Students</a></li>
          <li><a href="register_student.php">Register Student</a></li>
          <li><a href="delete_student.php">Delete Student</a></li>
          <li><a href="com_fed_visitor.php">View Complaints & Suggestions & Visitors</a></li>
          <li><a href="pending_approval.php">Pending Approvals</a></li>
          <li><a href="manage_due.php">Manage Fees</a></li>
          <li><a href="manage_slider.php">Manage Slider</a></li>
          <li><a href="#" onclick=" alert('Update Coming Soon......!')";>Manage Notices</a></li>
          <li><a href="admin_manage.php">Manage Admin</a></li>
          <li><a href="manage_manager.php">Manage Manager</a></li>
          <li><a href="alumni.php">Alumni</a></li>
          <li><a href="change_password.php">Change Password</a></li>
          <li><a href="logout.php"onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <h1>Welcome, <?php echo $admin_name; ?>!</h1>
      <p>Today is <?php echo date("l, d M Y"); ?></p>
      <section class="cards">
        <div class="card">
          <h3>Total Students</h3>
          <p id="total-students">--</p>
        </div>
        <div class="card">
          <h3>Pending Approvals</h3>
          <p id="pending-approvals">--</p>
        </div>
        <div class="card">
          <h3>Total Complaints</h3>
          <p id="total-complaints">--</p>
        </div>
        <div class="card">
          <h3>Total visitors</h3>
          <p id="total-suggestions">--</p>
        </div>
      </section>
    </main>
  </div>
  <script nonce="<?php echo bin2hex(random_bytes(16)); ?>">
  document.addEventListener('DOMContentLoaded', function () {
    fetch('admin_stats.php', {
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(function (res) {
      if (!res.ok) {
        throw new Error('Network response was not ok');
      }
      return res.json();
    })
    .then(function (data) {
      if (typeof data === 'object') {
        document.getElementById('total-students').textContent = data.students ?? '--';
        document.getElementById('pending-approvals').textContent = data.pending ?? '--';
        document.getElementById('total-complaints').textContent = data.complaints ?? '--';
        document.getElementById('total-suggestions').textContent = data.visitors ?? '--';
      } else {
        console.warn('Unexpected data format:', data);
      }
    })
    .catch(function (error) {
      console.error('Error fetching admin stats:', error);
    });
  });
</script>
  <script src="loder.js"></script>
<script>
// Prevent back-button after logout
window.history.pushState(null, null, document.URL);
window.addEventListener('popstate', function() {
    window.history.pushState(null, null, document.URL);
});
</script>
</body>
</html>