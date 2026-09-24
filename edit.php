<?php
require_once 'config/db.php';

$message = "";
$emp_id = $_GET['id'] ?? null;

// Redirect if no ID is provided in the URL
if (!$emp_id) {
    header("Location: index.php");
    exit();
}

// Fetch existing employee data
try {
    $stmt = $pdo->prepare("SELECT * FROM employee WHERE emp_id = ?");
    $stmt->execute([$emp_id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        die("Employee not found.");
    }
} catch (PDOException $e) {
    die("Error fetching employee details: " . $e->getMessage());
}

// Handle Form Submission (Update employee details)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['emp_first_name']);
    $lastName  = trim($_POST['emp_last_name']);
    $role      = trim($_POST['emp_role']);

    if (!empty($firstName) && !empty($lastName) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("UPDATE employee SET emp_first_name = ?, emp_last_name = ?, emp_role = ? WHERE emp_id = ?");
            $stmt->execute([$firstName, $lastName, $role, $emp_id]);
            
            // Redirect back to index.php with success message
            header("Location: index.php?msg=" . urlencode("Employee details updated successfully!"));
            exit();
        } catch (PDOException $e) {
            $message = "Error updating employee: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; color: #333; }
        .container { max-width: 600px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 8px 15px; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; color: white; display: inline-block; font-size: 14px; }
        .btn-green { background-color: #28a745; }
        .btn-green:hover { background-color: #218838; }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; }
        .message { margin-bottom: 15px; padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Employee Details</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="emp_first_name">First Name:</label>
            <input type="text" id="emp_first_name" name="emp_first_name" value="<?php echo htmlspecialchars($employee['emp_first_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="emp_last_name">Last Name:</label>
            <input type="text" id="emp_last_name" name="emp_last_name" value="<?php echo htmlspecialchars($employee['emp_last_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="emp_role">Role:</label>
            <input type="text" id="emp_role" name="emp_role" value="<?php echo htmlspecialchars($employee['emp_role']); ?>" required>
        </div>

        <button type="submit" class="btn btn-green">Update Details</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>