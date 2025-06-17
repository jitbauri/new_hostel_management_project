<?php
session_start();
require_once('../include/dbConnect.php');

if (!isset($_SESSION['manager_id'])) {
    header("Location: manager_login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];
    $password = $_POST['password'];
    $university_id = $_POST['university_id'];
    $guardian_name = $_POST['guardian_name'];
    $mobile = $_POST['mobile_number'];
    $guardian_mobile = $_POST['guardian_mobile_number'];
    $course = $_POST['course'];
    $room_no = $_POST['room_number'];
    
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
        } else {
            // Check if student is already pending or registered
            $check_pending = $conn->prepare("SELECT * FROM pending_students WHERE college_id = ?");
            $check_pending->bind_param("s", $college_id);
            $check_pending->execute();
            $pending_result = $check_pending->get_result();
            
            $check_students = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
            $check_students->bind_param("s", $college_id);
            $check_students->execute();
            $students_result = $check_students->get_result();
            
            if($pending_result->num_rows > 0 || $students_result->num_rows > 0) {
                $error = "This student is already registered or pending approval.";
            } elseif(move_uploaded_file($photo_tmp, $target_path)) {
                // Insert into pending_students table
                $stmt = $conn->prepare("INSERT INTO pending_students 
                    (name, college_id, password, university_id, guardian_name, 
                    mobile_number, guardian_mobile_number, course, photo, room_number) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", 
                    $name, $college_id, $password, $university_id, 
                    $guardian_name, $mobile, $guardian_mobile, 
                    $course, $photo_name, $room_no);
                
                if ($stmt->execute()) {
                    $success = "Student registration submitted successfully! Please wait for admin approval.";
                } else {
                    $error = "Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $error = "Failed to upload photo. Check directory permissions.";
            }
            $check_pending->close();
            $check_students->close();
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
    <link rel="stylesheet" href="css2/registerstudent.css">
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
            <input type="text" name="mobile_number" placeholder="Mobile Number" required>
            <input type="text" name="guardian_mobile_number" placeholder="Guardian Mobile Number" required>
            <input type="text" name="course" placeholder="Course" required>
            
            <label for="photo">Upload Photo (JPEG, PNG, GIF only):</label>
            <input type="file" name="photo" accept="image/jpeg,image/png,image/gif" required>
            
            <input type="text" name="room_number" placeholder="Room Number" required>
            
            <button type="submit">Submit for Approval</button>
        </form>
    </div>
</body>
</html>