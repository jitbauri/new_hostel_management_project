<?php
session_start();
if (!isset($_SESSION['college_id'])) {
    header("Location: ../signin.php");
    exit();
}
require_once '../include/dbConnect.php';

$success = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $regno = $_SESSION['college_id'];
    $vname = $_POST['visitor_name'];
    $relation = $_POST['relation'];
    $mobile = $_POST['mobile'];
    $vdate = $_POST['visit_date'];
    $vtime = $_POST['visit_time'];
    $purpose = $_POST['purpose'];

    $sql = "INSERT INTO visitors (college_id, visitor_name, relation, mobile, visit_date, visit_time, purpose)
            VALUES ('$regno', '$vname', '$relation', '$mobile', '$vdate', '$vtime', '$purpose')";
    if (mysqli_query($conn, $sql)) {
        $success = "✅ Visitor added successfully!";
    } else {
        $success = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Visitor</title>
  <link rel="stylesheet" href="css3/addvisitor.css">
</head>
<body>
  <div class="form-container">
    <h2>Add Visitor</h2>
    <?php if ($success): ?>
      <p class="msg"><?= $success ?></p>
    <?php endif; ?>
    <form method="post" id="visitorForm">
      <input type="text" name="visitor_name" placeholder="Visitor Name" required>
      <input type="text" name="relation" placeholder="Relation" required>
      <input type="text" name="mobile" placeholder="Mobile Number" required>
      <input type="date" name="visit_date" required>
      <input type="time" name="visit_time" required>
      <textarea name="purpose" placeholder="Purpose of Visit" required></textarea>
      <button type="submit">Add Visitor</button>
    </form>
  </div>

  <script>
    // Optional: JS for form reset after submit
    const form = document.getElementById('visitorForm');
    form.addEventListener('submit', function () {
      setTimeout(() => {
        form.reset();
      }, 500); // delay so success msg appears first
    });
  </script>
</body>
</html>
