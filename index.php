<?php
require_once 'config\db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = trim($_POST['emp_first_name']);
    $lastName  = trim($_POST['emp_last_name']);
    $role      = trim($_POST['emp_role']);

    if (!empty($firstName) && !empty($lastName) && !empty($role)) {
        try {
            $stmt =$pdo->prepare("INSERT INTO employee (emp_first_name, emp_last_name, emp_role) VALUES (?, ?, ?)");
            $stmt->execute([$firstName,$lastName, $role]);$message = "New employee added successfully!";
        } catch (PDOException $e) {$message = "Error adding employee: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }

}

try {
    $stmt =$pdo->query("SELECT * FROM employee ORDER BY emp_date_created DESC");
    $employees =$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching employees: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Management Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; color: #333; }
        .container { max-width: 700px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .message { margin-bottom: 15px; padding: 10px; background-color: #d4edda; color: #155724; border-radius: 4px; }
    </style>
</head>
<body>



<div class="container">
    <h2>Database Connection & Table Test</h2>
    <p style="color: green; font-weight: bold;">✔ Connected to database and table checked/created successfully via `db.php`!</p>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
<div style="margin-bottom: 20px;">
    <a href="search.php" class="btn btn-blue" style="background-color: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;">Search Employees</a>
</div>
    <h3>Add Test Employee</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="emp_first_name">First Name:</label>
            <input type="text" id="emp_first_name" name="emp_first_name" required>
        </div>
        <div class="form-group">
            <label for="emp_last_name">Last Name:</label>
            <input type="text" id="emp_last_name" name="emp_last_name" required>
        </div>
        <div class="form-group">
            <label for="emp_role">Role:</label>
            <input type="text" id="emp_role" name="emp_role" required>
        </div>
        <button type="submit">Save Employee</button>
    </form>
    <h3 style="margin-top: 30px;">Employee List</h3>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Role</th>
            <th>Date Created</th>
            <th>Actions</th> <!-- Column Header for Actions -->
        </tr>
    </thead>
    <tbody>
        <?php if (count($employees) > 0): ?>
            <?php foreach ($employees as $emp): ?>
                <tr>
                    <td><?php echo htmlspecialchars($emp['emp_id']); ?></td>
                    <td><?php echo htmlspecialchars($emp['emp_first_name']); ?></td>
                    <td><?php echo htmlspecialchars($emp['emp_last_name']); ?></td>
                    <td><?php echo htmlspecialchars($emp['emp_role']); ?></td>
                    <td><?php echo htmlspecialchars($emp['emp_date_created']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $emp['emp_id']; ?>" class="btn btn-warning" style="background-color: #ffc107; color: #212529; padding: 4px 10px; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px;">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align: center;">No employees found in the database.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</body>
</html>

