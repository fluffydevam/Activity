<?php
require_once 'config/db.php';

$emp_id = $_GET['id'] ?? null;

if ($emp_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM employee WHERE emp_id = ?");
        $stmt->execute([$emp_id]);
        
        header("Location: index.php?msg=" . urlencode("Employee deleted successfully!"));
        exit();
    } catch (PDOException $e) {
        die("Error deleting employee record: " . $e->getMessage());
    }
}

header("Location: index.php");
exit();