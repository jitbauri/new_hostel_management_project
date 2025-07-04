<?php
session_start();
include('../include/dbconnect.php');

// Redirect if not logged in
if (!isset($_SESSION['college_id'])) {
    header("Location: ../studentlogin.php");
    exit();
}

$college_id = $_SESSION['college_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suggestion = trim($_POST['suggestion']);

    if (!empty($suggestion)) {
        $stmt = $conn->prepare("INSERT INTO suggestions (college_id, suggestion) VALUES (?, ?)");
        $stmt->bind_param("ss", $college_id, $suggestion);
        if ($stmt->execute()) {
            $message = "Suggestion submitted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Please enter a suggestion.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Give Suggestion</title>
  <link rel="stylesheet" href="css3/give_suggestion.css">
</head>
<body>
  <div class="center">
    <h2>Give Suggestion</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>
    <form method="POST">
      <textarea name="suggestion" placeholder="Enter your suggestion here..." required></textarea>
      <input type="submit" value="Submit Suggestion">
    </form>
  </div>
</body>
</html>
