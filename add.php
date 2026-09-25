<?php
require_once 'config/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['emp_first_name'] ?? '');
    $lastName  = trim($_POST['emp_last_name'] ?? '');
    $role      = trim($_POST['emp_role'] ?? '');

    if (!empty($firstName) && !empty($lastName) && !empty($role)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO employee (emp_first_name, emp_last_name, emp_role) VALUES (?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $role]);
            
            header("Location: index.php?msg=" . urlencode("New employee added successfully!"));
            exit();
        } catch (PDOException $e) {
            $message = "Error adding employee: " . $e->getMessage();
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5" style="max-width: 600px;">

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-custom">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Add New Employee</h5>
            <a href="index.php" class="btn btn-sm btn-custom-outline">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="add.php">
                <div class="mb-3">
                    <label class="form-label text-secondary fw-medium">First Name</label>
                    <input type="text" name="emp_first_name" class="form-control" placeholder="Enter first name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary fw-medium">Last Name</label>
                    <input type="text" name="emp_last_name" class="form-control" placeholder="Enter last name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-secondary fw-medium">Role</label>
                    <input type="text" name="emp_role" class="form-control" placeholder="Enter employee role" required>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="index.php" class="btn btn-custom-outline px-3">Cancel</a>
                    <button type="submit" class="btn btn-custom-dark px-4">Save Employee</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>