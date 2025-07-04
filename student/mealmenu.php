<?php
session_start();
require_once('../include/dbConnect.php');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all weekly meals
$sql = "SELECT * FROM weekly_menu ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Meal Menu</title>
    <style>
        :root {
            --primary:rgba(39, 167, 63, 0.87);
            --secondary: #2980b9;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary);
        }
        
        .header h1 {
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .menu-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .menu-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .menu-table th {
            background-color: var(--primary);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }
        
        .menu-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .menu-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .menu-table tr:hover {
            background-color: #f1f9ff;
        }
        
        .day-column {
            font-weight: 600;
            color: var(--dark);
            width: 120px;
        }
        
        .meal-item {
            padding: 5px 0;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #777;
        }
        
        @media (max-width: 768px) {
            .menu-table {
                display: block;
                overflow-x: auto;
            }
            
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Weekly Meal Menu</h1>
            <p>Hostel meal schedule for the week</p>
        </div>
        
        <div class="menu-card">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="menu-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Lunch</th>
                            <th>Dinner</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td class="day-column"><?php echo htmlspecialchars($row['day']); ?></td>
        <td><?php echo htmlspecialchars($row['lunch_menu']); ?></td>
        <td><?php echo htmlspecialchars($row['dinner_menu']); ?></td>
    </tr>
<?php endwhile; ?>

                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No meal data available. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($conn);
?>