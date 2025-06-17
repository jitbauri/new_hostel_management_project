<?php
session_start();
if (!isset($_SESSION['college_id'])) {
    header("Location: login.php");
    exit();
}
$college_id = $_SESSION['college_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meal On/Off</title>
    <link rel="stylesheet" href="css3/meal.css">
</head>
<body>
    <div class="container">
        <h2>Meal Preference</h2>
        <form action="meal_on_off.php" method="POST">
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

<?php
if (isset($_POST['submit'])) {
    include '../include/dbconnect.php';
    $date = $_POST['meal_date'];
    $status = $_POST['status'];

    // Check if already submitted for same date
    $check = mysqli_query($conn, "SELECT * FROM meal_status WHERE student_id='$college_id' AND meal_date='$date'");
    if (mysqli_num_rows($check) > 0) {
        echo "<p class='error'>Meal status already submitted for this date.</p>";
    } else {
        $query = "INSERT INTO meal_status (student_id, meal_date, status) VALUES ('$college_id', '$date', '$status')";
        if (mysqli_query($conn, $query)) {
            echo "<p class='success'>Meal status submitted successfully!</p>";
        } else {
            echo "<p class='error'>Error submitting meal status.</p>";
        }
    }
}
?>
</body>
</html>
