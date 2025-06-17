<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once('../include/dbConnect.php');

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];
    $password = $_POST['password'];
    $university_id = $_POST['university_id'];
    $guardian_name = $_POST['guardian_name'];
    $mobile = $_POST['mobile'];
    $guardian_mobile = $_POST['guardian_mobile'];
    $course = $_POST['course'];
    $room_no = $_POST['room_no'];
    
    // File upload handling
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $photo_name = basename($_FILES['photo']['name']);
        $target_dir = '../uploads/students/';
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        $target_path = $target_dir . $photo_name;
        
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($photo_tmp);
        
        if(!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, PNG, and GIF images are allowed.";
        } elseif(move_uploaded_file($photo_tmp, $target_path)) {
            // Corrected database insert
            $stmt = $conn->prepare("INSERT INTO students 
                (name, college_id, password, university_id, guardian_name, 
                mobile_number, guardian_mobile_number, course, photo, room_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", 
                $name, $college_id, $password, $university_id, 
                $guardian_name, $mobile, $guardian_mobile, 
                $course, $photo_name, $room_no);
            
            if ($stmt->execute()) {
                $success = "Student registered successfully!";
            } else {
                $error = "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to upload photo. Check directory permissions.";
        }
    } else {
        $error = "Please select a valid photo file or file is too large.";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Student</title>
    <link rel="stylesheet" href="css1/Register_student.css">
    <style>
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input, button { padding: 10px; font-size: 16px; }
        button { background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register New Student</h2>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="college_id" placeholder="College ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="university_id" placeholder="University ID" required>
            <input type="text" name="guardian_name" placeholder="Guardian Name" required>
            <input type="text" name="mobile" placeholder="Mobile Number" required>
            <input type="text" name="guardian_mobile" placeholder="Guardian Mobile Number" required>
            <input type="text" name="course" placeholder="Course" required>
            
            <label for="photo">Upload Photo (JPEG, PNG, GIF only):</label>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/gif" required>
            
            <input type="text" name="room_no" placeholder="Room Number" required>
            
            <button type="submit">Register Student</button>
        </form>
    </div>
</body>
</html>