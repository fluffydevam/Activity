<?php
require_once 'config/db.php';

$search = trim($_GET['search'] ?? '');
$employees = [];

if (!empty($search)) {
    try {
        // Search by exact ID or partial First/Last Name
        $sql = "SELECT * FROM employee 
                WHERE emp_id = :exact_id 
                   OR emp_first_name LIKE :search 
                   OR emp_last_name LIKE :search 
                ORDER BY emp_id DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':exact_id' => is_numeric($search) ? (int)$search : 0,
            ':search'   => "%$search%"
        ]);
        
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error fetching search results: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Employees</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f9f9f9; color: #333; }
        .container { max-width: 750px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; border-radius: 4px; border: none; cursor: pointer; text-decoration: none; color: white; display: inline-block; }
        .btn-blue { background-color: #007bff; }
        .btn-blue:hover { background-color: #0056b3; }
        .btn-secondary { background-color: #6c757d; }
        .btn-secondary:hover { background-color: #5a6268; }
        .search-container { display: flex; gap: 8px; margin-top: 15px; }
        .search-container input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-actions">
        <h2>Search Employee Directory</h2>
        <a href="index.php" class="btn btn-secondary">← Back to Main List</a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="search.php">
        <div class="search-container">
            <input type="text" name="search" placeholder="Enter Employee ID, First Name, or Last Name..." value="<?php echo htmlspecialchars($search); ?>" required>
            <button type="submit" class="btn btn-blue">Search</button>
            <?php if (!empty($search)): ?>
                <a href="search.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Results Table -->
    <?php if (!empty($search)): ?>
        <h3 style="margin-top: 25px;">Search Results for "<?php echo htmlspecialchars($search); ?>"</h3>
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
    <?php else: ?>
        <p style="margin-top: 20px; color: #666;">Type an ID or name above to begin searching.</p>
    <?php endif; ?>
</div>

</body>
</html>