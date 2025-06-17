<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once('../include/dbConnect.php');

// Initialize variables
$student = null;
$students = [];
$search_error = "";
$delete_success = "";
$delete_error = "";
$search_type = "college_id";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Handle undefined index for search_value
    $search_value = trim($_POST['search_value'] ?? '');
    $search_type = $_POST['search_type'] ?? 'college_id';

    if (isset($_POST['search'])) {
        if (empty($search_value)) {
            $search_error = "Please enter a search value.";
        } else {
            if ($search_type === "college_id") {
                $stmt = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
                $stmt->bind_param("s", $search_value);
                $stmt->execute();
                $result = $stmt->get_result();
                $student = $result->fetch_assoc();
                if (!$student) {
                    $search_error = "No student found with this College ID.";
                }
                $stmt->close();
            } else {
                $search_name = "%" . $search_value . "%";
                $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? LIMIT 20");
                $stmt->bind_param("s", $search_name);
                $stmt->execute();
                $result = $stmt->get_result();
                $students = $result->fetch_all(MYSQLI_ASSOC);
                if (empty($students)) {
                    $search_error = "No students found with this name.";
                }
                $stmt->close();
            }
        }
    }

    if (isset($_POST['delete'])) {
        $college_id = $_POST['college_id'] ?? '';
        
        try {
            // Begin transaction
            $conn->begin_transaction();

            // 1. First delete from students
            $stmt = $conn->prepare("DELETE FROM students WHERE college_id = ?");
            $stmt->bind_param("s", $college_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete student: " . $stmt->error);
            }
            $stmt->close();

            // 2. Check if student exists in alumni first
            $stmt = $conn->prepare("SELECT college_id FROM alumni WHERE college_id = ?");
            $stmt->bind_param("s", $college_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 0) {
                // 3. Get student data and insert into alumni
                $stmt = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
                $stmt->bind_param("s", $college_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $student = $result->fetch_assoc();
                $stmt->close();

                if ($student) {
                    $stmt = $conn->prepare("INSERT INTO alumni (
                        name, college_id, phone, guardian_phone, 
                        room_number, course, photo, password
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("ssssssss", 
                        $student['name'],
                        $student['college_id'],
                        $student['mobile_number'],
                        $student['guardian_mobile_number'],
                        $student['room_number'],
                        $student['course'],
                        $student['photo'],
                        $student['password']
                    );
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to move to alumni: " . $stmt->error);
                    }
                    $stmt->close();
                }
            }

            $conn->commit();
            $delete_success = "Student deleted and moved to alumni successfully.";
            $student = null;
            $students = [];
        } catch (Exception $e) {
            $conn->rollback();
            $delete_error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="css1/deletestudent.css">
</head>
<body>
<div class="container">
    <h2>Delete Student and Move to Alumni</h2>

    <form method="POST" class="search-form">
        <div class="search-options">
            <label>
                <input type="radio" name="search_type" value="college_id" <?php echo ($search_type === 'college_id') ? 'checked' : ''; ?>> College ID
            </label>
            <label>
                <input type="radio" name="search_type" value="name" <?php echo ($search_type === 'name') ? 'checked' : ''; ?>> Name
            </label>
        </div>
        <input type="text" name="search_value" placeholder="<?php echo ($search_type === 'college_id') ? 'Enter College ID' : 'Enter Name'; ?>" 
               value="<?php echo isset($_POST['search_value']) ? htmlspecialchars($_POST['search_value']) : ''; ?>" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($search_error): ?>
        <div class="error"><?php echo $search_error; ?></div>
    <?php endif; ?>

    <?php if ($delete_success): ?>
        <div class="success"><?php echo $delete_success; ?></div>
    <?php endif; ?>

    <?php if ($delete_error): ?>
        <div class="error"><?php echo $delete_error; ?></div>
    <?php endif; ?>

    <?php if ($student): ?>
        <div class="student-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
            <p><strong>College ID:</strong> <?php echo htmlspecialchars($student['college_id']); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($student['mobile_number'] ?? 'N/A'); ?></p>
            <p><strong>Guardian Mobile:</strong> <?php echo htmlspecialchars($student['guardian_mobile_number'] ?? 'N/A'); ?></p>
            <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
            <p><strong>Room No:</strong> <?php echo htmlspecialchars($student['room_number'] ?? 'N/A'); ?></p>
        </div>

        <form method="POST" class="delete-form">
            <input type="hidden" name="college_id" value="<?php echo htmlspecialchars($student['college_id']); ?>">
            <button type="submit" name="delete" onclick="return confirm('Are you sure to delete this student?')">Delete and Move to Alumni</button>
        </form>
    <?php elseif (!empty($students)): ?>
        <div class="search-results">
            <h3>Search Results</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>College ID</th>
                        <th>Course</th>
                        <th>Room No</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo htmlspecialchars($s['college_id']); ?></td>
                            <td><?php echo htmlspecialchars($s['course']); ?></td>
                            <td><?php echo htmlspecialchars($s['room_number'] ?? 'N/A'); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="college_id" value="<?php echo htmlspecialchars($s['college_id']); ?>">
                                    <button type="submit" name="delete" onclick="return confirm('Are you sure to delete this student?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>