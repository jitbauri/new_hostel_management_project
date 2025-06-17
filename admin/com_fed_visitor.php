<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once __DIR__ . '/../include/dbConnect.php';

// Fetch complaints with correct column names matching your database
$complaints = [];
$result = $conn->query("SELECT college_id, title, description, category, complain_date, status, created_at FROM complaints ORDER BY created_at DESC");
if ($result === false) {
    die("Error fetching complaints: " . $conn->error);
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

// Fetch suggestions with proper column names
$suggestions = [];
$result = $conn->query("SELECT college_id, suggestion, submitted_at FROM suggestions ORDER BY submitted_at DESC");
if ($result === false) {
    die("Error fetching suggestions: " . $conn->error);
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
}

// Fetch visitors with proper column names
$visitors = [];
$result = $conn->query("SELECT college_id, visitor_name, relation, mobile, visit_date, visit_time, purpose FROM visitors ORDER BY created_at DESC");
if ($result === false) {
    die("Error fetching visitors: " . $conn->error);
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $visitors[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Feedback & Visitor Records</title>
    <link rel="stylesheet" href="css1/feed_visitor.css">
       <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
        }
        h2 {
            color: #495057;
            margin-top: 40px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #adb5bd;
        }
        .box {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 5px solid;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .complaint {
            border-color: #dc3545;
        }
        .suggestion {
            border-color: #28a745;
        }
        .visitor {
            border-color: #17a2b8;
        }
        strong {
            color: #212529;
        }
        p {
            margin: 8px 0;
            color: #495057;
        }
        .no-records {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
<body>
<div class="container">
    <h1>Admin Dashboard: Feedback & Visitors</h1>

    <!-- Complaints Section -->
    <section>
        <h2>🟥 Complaints</h2>
        <?php if (!empty($complaints)): ?>
            <?php foreach ($complaints as $complaint): ?>
                <div class="box complaint">
                    <p><strong>College ID:</strong> <?= htmlspecialchars($complaint['college_id'] ?? 'N/A') ?></p>
                    <p><strong>Title:</strong> <?= htmlspecialchars($complaint['title'] ?? '') ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($complaint['description'] ?? '') ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($complaint['category'] ?? '') ?></p>
                    <p><strong>Date:</strong> <?= htmlspecialchars($complaint['complain_date'] ?? '') ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($complaint['status'] ?? 'Pending') ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-records">No complaints submitted.</div>
        <?php endif; ?>
    </section>

     <!-- Suggestions Section -->
    <section>
        <h2>🟩 Suggestions</h2>
        <?php if (!empty($suggestions)): ?>
            <?php foreach ($suggestions as $suggestion): ?>
                <div class="box suggestion">
                    <p><strong>College ID:</strong> <?= htmlspecialchars($suggestion['college_id'] ?? 'N/A') ?></p>
                    <p><strong>Suggestion:</strong> <?= htmlspecialchars($suggestion['suggestion'] ?? '') ?></p>
                    <p><strong>Submitted At:</strong> <?= htmlspecialchars($suggestion['submitted_at'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-records">No suggestions submitted.</div>
        <?php endif; ?>
    </section>

    <!-- Visitors Section -->
    <section>
        <h2>🟦 Visitors</h2>
        <?php if (!empty($visitors)): ?>
            <?php foreach ($visitors as $visitor): ?>
                <div class="box visitor">
                    <p><strong>Student Reg No:</strong> <?= htmlspecialchars($visitor['student_regno'] ?? 'N/A') ?></p>
                    <p><strong>Visitor Name:</strong> <?= htmlspecialchars($visitor['visitor_name'] ?? '') ?></p>
                    <p><strong>Relation:</strong> <?= htmlspecialchars($visitor['relation'] ?? '') ?></p>
                    <p><strong>Mobile:</strong> <?= htmlspecialchars($visitor['mobile'] ?? '') ?></p>
                    <p><strong>Visit Date:</strong> <?= htmlspecialchars($visitor['visit_date'] ?? '') ?></p>
                    <p><strong>Visit Time:</strong> <?= htmlspecialchars($visitor['visit_time'] ?? '') ?></p>
                    <p><strong>Purpose:</strong> <?= htmlspecialchars($visitor['purpose'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-records">No visitors recorded.</div>
        <?php endif; ?>
    </section>
</div>
</body>
</html>