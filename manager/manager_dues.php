<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session check for manager
session_start();
if (!isset($_SESSION['manager_id'])) {
    header("Location: ../manager_login.php");
    exit();
}

require_once('../include/dbConnect.php');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student = null;
$message = "";
$dues = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Search Student
    if (isset($_POST['search'])) {
        $search_term = trim($_POST['search_term']);
        if (!empty($search_term)) {
            $search = "%" . $search_term . "%";
            $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR college_id LIKE ?");
            if ($stmt) {
                $stmt->bind_param("ss", $search, $search);
                $stmt->execute();
                $result = $stmt->get_result();
                $student = $result->fetch_assoc();
                $stmt->close();

                if ($student) {
                    $stmt = $conn->prepare("SELECT * FROM dues WHERE college_id = ?");
                    $stmt->bind_param("s", $student['college_id']);
                    $stmt->execute();
                    $dues_result = $stmt->get_result();
                    $dues = $dues_result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                } else {
                    $message = "No student found with that name or ID.";
                }
            } else {
                $message = "Database error: " . $conn->error;
            }
        } else {
            $message = "Please enter a search term.";
        }
    }

    // Add Due
    if (isset($_POST['add_due'])) {
        $college_id = $_POST['college_id'];
        $amount = (float)$_POST['amount'];
        $due_date = $_POST['due_date'];

        $stmt = $conn->prepare("INSERT INTO dues (college_id, amount, due_date, is_paid) VALUES (?, ?, ?, 0)");
        if ($stmt) {
            $stmt->bind_param("sds", $college_id, $amount, $due_date);
            $stmt->execute();
            $message = $stmt->affected_rows > 0 ? "Due added successfully!" : "Error adding due.";
            $stmt->close();
        }

        // Refresh data
        $stmt = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM dues WHERE college_id = ?");
        $stmt->bind_param("s", $college_id);
        $stmt->execute();
        $dues_result = $stmt->get_result();
        $dues = $dues_result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    // Delete Due
    if (isset($_POST['delete_due']) && !empty($_POST['due_id'])) {
        $due_id = (int)$_POST['due_id'];

        // Get student ID
        $stmt = $conn->prepare("SELECT college_id FROM dues WHERE due_id = ?");
        $stmt->bind_param("i", $due_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $college_id = $row['college_id'];
            $stmt = $conn->prepare("DELETE FROM dues WHERE due_id = ?");
            $stmt->bind_param("i", $due_id);
            $stmt->execute();
            $message = $stmt->affected_rows > 0 ? "Due removed successfully." : "Error removing due.";
            $stmt->close();

            // Refresh data
            $stmt = $conn->prepare("SELECT * FROM students WHERE college_id = ?");
            $stmt->bind_param("s", $college_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM dues WHERE college_id = ?");
            $stmt->bind_param("s", $college_id);
            $stmt->execute();
            $dues_result = $stmt->get_result();
            $dues = $dues_result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }
    }
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

    <?php if (!empty($message)): ?>
        <div class="message <?php echo (strpos($message, 'Error') !== false) ? 'error' : 'success'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="search-form">
        <input type="text" name="search_term" placeholder="Enter Name or College ID" required
               value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>">
        <button type="submit" name="search">Search</button>
    </form>

    <?php if ($student): ?>
        <div class="student-info">
            <h3>Student Details</h3>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
            <p><strong>College ID:</strong> <?php echo htmlspecialchars($student['college_id']); ?></p>
        </div>

        <form method="POST" class="add-due-form" onsubmit="return validateDueForm()">
            <input type="hidden" name="college_id" value="<?php echo htmlspecialchars($student['college_id']); ?>">

            <div class="form-group">
                <label for="amount">Amount (₹)</label>
                <input type="number" id="amount" name="amount" placeholder="Due Amount"
                       min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" required min="<?php echo date('Y-m-d'); ?>">
            </div>

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
                            <input type="hidden" name="due_id" value="<?php echo htmlspecialchars($due['due_id']); ?>">
                            <button type="submit" name="delete_due">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No dues found for this student.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function validateDueForm() {
    const amount = document.getElementById('amount').value;
    const dueDate = document.getElementById('due_date').value;

    if (!amount || amount <= 0) {
        alert("Please enter a valid amount");
        return false;
    }

    if (!dueDate) {
        alert("Please select a due date");
        return false;
    }

    return true;
}
</script>
</body>
</html>