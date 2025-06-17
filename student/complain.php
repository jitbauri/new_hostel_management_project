<?php
session_start();
if (!isset($_SESSION['college_id'])) {
    header("Location: ../studentlogin.php");
    exit();
}
include ('../include/dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $college_id = $_SESSION['college_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $category = $_POST['category'];

    $stmt = $conn->prepare("INSERT INTO complaints (college_id, title, description, complain_date, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $college_id, $title, $description, $complain_date, $category);

    if ($stmt->execute()) {
        $message = "Complaint registered successfully.";
    } else {
        $message = "Error registering complaint.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Complaint</title>
    <link rel="stylesheet" href="css3/register_complain.css">
</head>
<body>
    <div class="container">
        <h2>Register Complaint</h2>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Complaint Title:</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required></textarea>
            </div>
            <div class="form-group">
                <label>Date:</label>
                <input type="date" name="date" required>
            </div>
            <div class="form-group">
                <label>Category:</label>
                <select name="category" required>
                    <option value="Electrical">Electrical</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Internet">Internet</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <input type="submit" value="Submit Complaint">
        </form>
    </div>
</body>
</html>
