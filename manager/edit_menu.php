<?php
session_start();
require_once('../include/dbConnect.php');

// Check login
if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$success = "";
$error = "";

// Update weekly menu on form submit
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_menu'])) {
    foreach ($_POST['menu'] as $day => $meals) {
        $lunch = trim($meals['lunch']);
        $dinner = trim($meals['dinner']);

        $stmt = $conn->prepare("UPDATE weekly_menu SET lunch_menu = ?, dinner_menu = ? WHERE day = ?");
        $stmt->bind_param("sss", $lunch, $dinner, $day);
        if (!$stmt->execute()) {
            $error = "Failed to update menu for $day.";
        }
        $stmt->close();
    }

    if (!$error) {
        $success = "✅ Weekly menu updated successfully!";
    }
}

// Fetch menu
$menu = [];
$result = $conn->query("SELECT * FROM weekly_menu ORDER BY FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')");
while ($row = $result->fetch_assoc()) {
    $menu[$row['day']] = [
        'lunch' => $row['lunch_menu'],
        'dinner' => $row['dinner_menu']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Weekly Menu</title>
    <link rel="stylesheet" href="css2/edit_menu.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
            padding-top: 30px;
        }
        input[readonly] {
            background-color: #f5f5f5;
            border: none;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        button {
            padding: 10px 18px;
            margin: 10px 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Weekly Menu</h2>

    <?php if ($success): ?>
        <div class="success" style="color: green;"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error" style="color: red;"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" id="menuForm">
        <table>
            <tr>
                <th>Day</th>
                <th>Lunch</th>
                <th>Dinner</th>
            </tr>
            <?php foreach ($menu as $day => $meals): ?>
                <tr>
                    <td><strong><?php echo $day; ?></strong></td>
                    <td><input type="text" name="menu[<?php echo $day; ?>][lunch]" value="<?php echo htmlspecialchars($meals['lunch']); ?>" readonly></td>
                    <td><input type="text" name="menu[<?php echo $day; ?>][dinner]" value="<?php echo htmlspecialchars($meals['dinner']); ?>" readonly></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div style="text-align: center;">
            <button type="button" onclick="enableEdit()">Edit Menu</button>
            <button type="submit" name="update_menu" id="saveBtn" style="display: none;">Save Changes</button>
        </div>
    </form>
</div>

<script>
function enableEdit() {
    const inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach(input => input.removeAttribute('readonly'));

    document.getElementById("saveBtn").style.display = "inline-block";
}
</script>
</body>
</html>
