<?php
require_once 'config/db.php';

$message = $_GET['msg'] ?? "";
$search  = trim($_GET['search'] ?? '');

try {
    if (!empty($search)) {
        $sql = "SELECT * FROM employee 
                WHERE emp_id = :exact_id 
                   OR emp_first_name LIKE :search 
                   OR emp_last_name LIKE :search 
                   OR CONCAT(emp_first_name, ' ', emp_last_name) LIKE :search
                ORDER BY emp_id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':exact_id' => is_numeric($search) ? (int)$search : 0,
            ':search'   => "%$search%"
        ]);
    } else {
        $stmt = $pdo->query("SELECT * FROM employee ORDER BY emp_id ASC");
    }
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching employees: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 1000px;">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--color-dark-slate);">Employee Management System</h3>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-custom">
        <div class="card-header-custom d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h5 class="mb-0">Employee List</h5>
            
            <div class="d-flex align-items-center gap-2">
                <form method="GET" action="index.php" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search for ..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-sm btn-custom-outline">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <a href="add.php" class="btn btn-sm btn-custom-outline px-3 d-flex align-items-center gap-1">
                    Add <i class="bi bi-plus-lg"></i>
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Employee Name</th>
                            <th>Role</th>
                            <th>Start Date</th>
                            <th class="text-center" style="width: 100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($employees) > 0): ?>
                            <?php foreach ($employees as $emp): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?php echo htmlspecialchars($emp['emp_id']); ?></td>
                                    <td><?php echo htmlspecialchars($emp['emp_first_name'] . ' ' . $emp['emp_last_name']); ?></td>
                                    <td>
                                        <span class="role-badge"><?php echo htmlspecialchars($emp['emp_role']); ?></span>
                                    </td>
                                    <td class="text-secondary"><?php echo htmlspecialchars($emp['emp_date_created']); ?></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?php echo urlencode($emp['emp_id']); ?>" class="action-icon me-1" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo urlencode($emp['emp_id']); ?>" onclick="return confirm('Are you sure you want to delete this employee?');" class="action-icon delete-icon" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No employees found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>