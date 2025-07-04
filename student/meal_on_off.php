<?php
session_start();
if (!isset($_SESSION['college_id'])) {
    header("Location: signin.php");
    exit();
}
$college_id = $_SESSION['college_id'];

include '../include/dbconnect.php';

$message = ""; // Initialize message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['meal_date'];
    $status = $_POST['status'];
    $updated_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("SELECT * FROM meal_status WHERE college_id = ? AND DATE(updated_at) = ?");
    $stmt->bind_param("ss", $college_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE meal_status SET status = ?, updated_at = ? WHERE college_id = ? AND DATE(updated_at) = ?");
        $update->bind_param("ssss", $status, $updated_at, $college_id, $date);
        if ($update->execute()) {
            $message = "✅ Meal status updated successfully!";
        } else {
            $message = "❌ Failed to update meal status.";
        }
    } else {
        $insert = $conn->prepare("INSERT INTO meal_status (college_id, status, updated_at) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $college_id, $status, $updated_at);
        if ($insert->execute()) {
            $message = "✅ Meal status submitted successfully!";
        } else {
            $message = "❌ Failed to submit meal status.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meal On/Off</title>
    <link rel="stylesheet" href="css3/meal_status.css">
</head>
<body>
    <div class="container">
        <h2>Meal Preference</h2>

        <?php if (!empty($message)): ?>
            <p class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="meal_date">Select Date:</label>
            <input type="date" name="meal_date" required>

            <label for="status">Meal Status:</label>
            <select name="status" required>
                <option value="on">Meal On</option>
                <option value="off">Meal Off</option>
            </select>

            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>
</html>
