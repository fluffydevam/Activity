<?php
require_once 'config/db.php';

$search = trim($_GET['search'] ?? '');
$employees = [];

if (!empty($search)) {
    try {
        $sql = "SELECT * FROM employee 
                WHERE emp_id = :exact_id 
                   OR emp_first_name LIKE :search 
                   OR emp_last_name LIKE :search 
                ORDER BY emp_id ASC";
        
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Employees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Search Directory</h2>
        <a href="index.php" class="btn btn-secondary">← Back to List</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="search.php" class="row g-2">
                <div class="col-sm-9">
                    <input type="text" name="search" class="form-control" placeholder="Enter ID, First Name, or Last Name..." value="<?php echo htmlspecialchars($search); ?>" required>
                </div>
                <div class="col-sm-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                    <?php if (!empty($search)): ?>
                        <a href="search.php" class="btn btn-outline-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($search)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">Results for "<?php echo htmlspecialchars($search); ?>"</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                <th>Date Created</th>
                                <th class="text-center">Actions</th>
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
                                        <td class="text-center">
                                            <a href="edit.php?id=<?php echo $emp['emp_id']; ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                            <a href="delete.php?id=<?php echo $emp['emp_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="btn btn-danger btn-sm">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-3">No matching employees found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>