<?php
session_start();
require_once('../include/dbConnect.php');

if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$success = "";
$error = "";

// Update menu when form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_menu'])) {
    foreach ($_POST['menu'] as $day => $meals) {
        foreach ($meals as $meal_type => $description) {
            $stmt = $conn->prepare("UPDATE meal_menu SET menu_items = ? WHERE day = ? AND meal_type = ?");
            $stmt->bind_param("sss", $description, $day, $meal_type);
            if (!$stmt->execute()) {
                $error = "Failed to update some menu items.";
            }
            $stmt->close();
        }
    }
    if (!$error) $success = "Weekly menu updated successfully!";
}

// Fetch menu from database
$menu = [];
$result = $conn->query("SELECT * FROM meal_menu ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), FIELD(meal_type, 'Lunch', 'Dinner')");
while ($row = $result->fetch_assoc()) {
    $menu[$row['day']][$row['meal_type']] = $row['menu_items'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Weekly Menu</title>
    <link rel="stylesheet" href="css2/edit_menu.css">
</head>
<body>
<div class="container">
    <h2>Edit Weekly Menu</h2>

    <?php if ($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>

    <form method="POST">
        <table>
            <tr>
                <th>Day</th>
                <th>Lunch</th>
                <th>Dinner</th>
            </tr>
            <?php foreach ($menu as $day => $meals): ?>
                <tr>
                    <td><?php echo $day; ?></td>
                    <td><input type="text" name="menu[<?php echo $day; ?>][Lunch]" value="<?php echo htmlspecialchars($meals['Lunch']); ?>"></td>
                    <td><input type="text" name="menu[<?php echo $day; ?>][Dinner]" value="<?php echo htmlspecialchars($meals['Dinner']); ?>"></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button type="submit" name="update_menu">Update Menu</button>
    </form>
</div>
</body>
</html>
