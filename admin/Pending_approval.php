<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once('../include/dbConnect.php');

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_id'])) {
    $id = $_POST['approve_id'];

    $stmt = $conn->prepare("SELECT * FROM pending_students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Fix column names to match your database structure
        $insert = $conn->prepare("INSERT INTO students 
            (name, college_id, password, university_id, guardian_name, 
            mobile_number, guardian_mobile_number, course, photo, room_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $insert->bind_param("ssssssssss", 
            $row['name'], 
            $row['college_id'], 
            $row['password'], 
            $row['university_id'], 
            $row['guardian_name'], 
            $row['mobile_number'], 
            $row['guardian_mobile_number'], 
            $row['course'], 
            $row['photo'], 
            $row['room_number']
        );

        if ($insert->execute()) {
            $delete = $conn->prepare("DELETE FROM pending_students WHERE id = ?");
            $delete->bind_param("i", $id);
            if ($delete->execute()) {
                $success = "Student approved successfully!";
            } else {
                $error = "Failed to remove from pending list.";
            }
        } else {
            $error = "Failed to approve student: " . $conn->error;
        }
        $insert->close();
        $delete->close();
    }
    $stmt->close();
}

$sql = "SELECT * FROM pending_students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Approvals</title>
    <link rel="stylesheet" href="css1/pendingapproval.css">
    <style>
        .error { color: red; }
        .success { color: green; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        img { max-width: 80px; max-height: 80px; }
        button { padding: 8px 12px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Student Approvals</h2>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>College ID</th>
                    <th>University ID</th>
                    <th>Course</th>
                    <th>Room No</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="../uploads/students/<?php echo htmlspecialchars($row['photo']); ?>" alt="Student Photo"></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['college_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['university_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="approve_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Approve</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>