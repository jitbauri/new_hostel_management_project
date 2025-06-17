<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once('../include/dbConnect.php'); // Fixed path (line 8)

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Students</title>
    <link rel="stylesheet" href="css1/view_all_students.css">
</head>
<body>
    <div class="container">
        <h2>All Registered Students</h2>
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>College ID</th>
                    <th>University ID</th>
                    <th>Guardian Name</th>
                    <th>Mobile</th>
                    <th>Guardian Mobile</th>
                    <th>Course</th>
                    <th>Room No</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="../uploads/students/<?php echo $row['photo']; ?>" alt="Student Photo" width="50"></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['college_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['university_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['guardian_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['guardian_mobile_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['course']); ?></td>
                            <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No students found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>