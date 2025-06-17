<?php
session_start();
if (!isset($_SESSION['admin_college_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

require_once __DIR__ . '/../include/dbConnect.php';

// Run this only once to add the column (then you can remove or comment it out)
// $conn->query("ALTER TABLE alumni ADD COLUMN left_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alumni Records</title>
    <link rel="stylesheet" href="../css/alumni.css">
   <style>
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>

</head>
<body>
    <div class="container">
        <h2>Hostel Alumni Records</h2>

        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>College ID</th>
                    <th>Phone</th>
                    <th>Guardian Phone</th>
                    <th>Course</th>
                    <th>Room No</th>
                    <th>Left On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM alumni ORDER BY left_on DESC";
                $result = $conn->query($query);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='../uploads/students/" . htmlspecialchars($row['photo'] ?? 'default.jpg') . "' alt='Alumni Photo' class='profile-img'></td>";
                        echo "<td>" . htmlspecialchars($row['name'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['college_id'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['guardian_phone'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['course'] ?? '') . "</td>";
                        echo "<td>" . htmlspecialchars($row['room_number'] ?? '') . "</td>";
                        // Format the date for better readability
                        $left_on = isset($row['left_on']) ? date('M d, Y h:i A', strtotime($row['left_on'])) : 'N/A';
                        echo "<td>" . htmlspecialchars($left_on) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align:center;padding:20px;'>No alumni records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>