<?php
session_start();
require_once('../include/dbConnect.php');

if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$sql = "SELECT name, college_id, guardian_name, mobile_number, guardian_mobile_number FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Student Contacts</title>
    <link rel="stylesheet" href="css2/view_students.css">
</head>
<body>
    <div class="container">
        <h2>Student Contacts</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>College ID</th>
                    <th>Guardian Name</th>
                    <th>Mobile Number</th>
                    <th>Guardian Mobile</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['college_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['guardian_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['guardian_mobile_number']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No student data found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
