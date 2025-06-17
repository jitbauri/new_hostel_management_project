<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}
require_once('../include/dbConnect.php');

$student = null;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Search for student
    if (isset($_POST['search'])) {
        $search_term = trim($_POST['search_term']);
        if (!empty($search_term)) {
            $search = "%" . $search_term . "%";
            $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR college_id LIKE ?");
            $stmt->bind_param("ss", $search, $search);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();
            $stmt->close();
            
            if (!$student) {
                $message = "No student found with that name or ID.";
            }
        } else {
            $message = "Please enter a search term.";
        }
    }

    // Add due - CHANGED college_id to student_id
    if (isset($_POST['add_due']) && !empty($_POST['student_id'])) {
        $student_id = (int)$_POST['student_id'];
        $amount = (float)$_POST['amount'];
        $due_date = $_POST['due_date'];
        
        $stmt = $conn->prepare("INSERT INTO dues (student_id, amount, due_date, is_paid) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("ids", $student_id, $amount, $due_date);
        if ($stmt->execute()) {
            $message = "Due added successfully.";
        } else {
            $message = "Error adding due: " . $stmt->error;
        }
        $stmt->close();
        
        // Refresh student data after adding due
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();
    }

    // Delete due
    if (isset($_POST['delete_due']) && !empty($_POST['due_id'])) {
        $due_id = (int)$_POST['due_id'];
        $stmt = $conn->prepare("DELETE FROM dues WHERE due_id = ?");
        $stmt->bind_param("i", $due_id);
        if ($stmt->execute()) {
            $message = "Due removed successfully.";
        } else {
            $message = "Error removing due: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch dues if student found - CHANGED to use student_id
$dues = [];
if ($student && isset($student['student_id'])) {
    $stmt = $conn->prepare("SELECT * FROM dues WHERE student_id = ?");
    $stmt->bind_param("i", $student['student_id']);
    $stmt->execute();
    $dues_result = $stmt->get_result();
    if ($dues_result) {
        $dues = $dues_result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Dues</title>
  <style>
    .container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 25px;
        text-align: center;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    h3, h4 {
        color: #2980b9;
        font-size: 22px;
        margin: 20px 0 15px;
    }

    .search-form {
        margin-bottom: 30px;
        padding: 25px;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .add-due-form {
        margin-bottom: 30px;
        padding: 25px;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s;
    }

    .add-due-form input[type="number"],
    .add-due-form input[type="date"] {
        width: 150px;
        padding: 12px 15px;
        margin: 10px 0;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 6px;
        transition: border-color 0.3s;
    }

    input:focus {
        border-color: #3498db;
        outline: none;
    }

    button {
        padding: 12px 25px;
        margin: 10px 5px 10px 0;
        font-size: 16px;
        font-weight: 600;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .add-due-form button {
        flex-grow: 1;
        max-width: 200px;
    }

    button:hover {
        background: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    button[name="delete_due"] {
        background: #e74c3c;
        padding: 8px 15px;
        font-size: 14px;
    }

    button[name="delete_due"]:hover {
        background: #c0392b;
    }

    .student-info {
        background: #e8f4fc;
        padding: 25px;
        margin-bottom: 30px;
        border-radius: 8px;
        border-left: 5px solid #3498db;
    }

    .student-info p {
        font-size: 18px;
        margin: 12px 0;
        color: #34495e;
    }

    .student-info strong {
        color: #2c3e50;
        font-weight: 600;
    }

    .dues-list {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }

    .dues-list li {
        padding: 20px;
        margin: 15px 0;
        background: #ffffff;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 17px;
    }

    .dues-list li form {
        display: inline;
        margin-left: 15px;
    }

    .message {
        padding: 15px;
        margin: 20px 0;
        border-radius: 6px;
        font-size: 16px;
        text-align: center;
    }

    .success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
            margin: 15px;
        }
        
        .dues-list li {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .dues-list li form {
            margin: 10px 0 0 0;
            align-self: flex-end;
        }

        .add-due-form input[type="number"],
        .add-due-form input[type="date"],
        .add-due-form button {
            width: 100%;
            max-width: 100%;
        }
    }
</style>
</head>
<body>
<div class="container">
    <h2>Manage Student Dues</h2>

    <form method="POST" class="search-form">
        <input type="text" name="search_term" placeholder="Enter Name or College ID" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($student): ?>
        <div class="student-info">
            <h3>Student Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
            <p><strong>College ID:</strong> <?php echo htmlspecialchars($student['college_id']); ?></p>
        </div>

        <form method="POST" class="add-due-form">
            <!-- CHANGED from college_id to student_id -->
            <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
            <input type="number" name="amount" placeholder="Due Amount" min="0" step="0.01" required>
            <input type="date" name="due_date" required>
            <button type="submit" name="add_due">Add Due</button>
        </form>

        <h4>Current Dues</h4>
        <?php if (!empty($dues)): ?>
            <ul class="dues-list">
                <?php foreach ($dues as $due): ?>
                    <li>
                        Amount: ₹<?php echo number_format($due['amount'], 2); ?> | 
                        Due Date: <?php echo htmlspecialchars($due['due_date']); ?> |
                        Status: <?php echo $due['is_paid'] ? 'Paid' : 'Unpaid'; ?>
                        <form method="POST" style="display:inline;">
                            <!-- CHANGED from id to due_id -->
                            <input type="hidden" name="due_id" value="<?php echo $due['due_id']; ?>">
                            <button type="submit" name="delete_due">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No dues found for this student.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($message): ?>
        <p class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
</div>
</body>
</html>