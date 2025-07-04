<?php
session_start();
header("Content-Type: application/json");

// Ensure admin is logged in
if (!isset($_SESSION['admin_college_id'])) {
    echo json_encode([
        "students" => "--",
        "pending" => "--",
        "complaints" => "--",
        "suggestions" => "--"
    ]);
    exit();
}

require_once('../include/dbConnect.php');

$data = [
    "students" => 0,
    "pending" => 0,
    "complaints" => 0,
    "suggestions" => 0
];

// Get total students
$result = $conn->query("SELECT COUNT(*) as total FROM students WHERE is_alumni = 0");
$row = $result->fetch_assoc();
$data['students'] = (int)$row['total'];

// Get pending approvals
$result = $conn->query("SELECT COUNT(*) as total FROM pending_students");
$row = $result->fetch_assoc();
$data['pending'] = (int)$row['total'];

// Get complaints
$result = $conn->query("SELECT COUNT(*) as total FROM complaints");
$row = $result->fetch_assoc();
$data['complaints'] = (int)$row['total'];

// Get suggestions
$result = $conn->query("SELECT COUNT(*) as total FROM visitors");
$row = $result->fetch_assoc();
$data['visitors'] = (int)$row['total'];

echo json_encode($data);
?>
