
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