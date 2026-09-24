<?php
// Database configuration credentials
$host = 'localhost';$dbname = 'activity'; 
$username = 'root';   
$password = '';       
try {
    
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");

    
    $pdo->exec("USE `$dbname`");

    
    $sql = "CREATE TABLE IF NOT EXISTS employee (
        emp_id INT AUTO_INCREMENT PRIMARY KEY,
        emp_first_name VARCHAR(50) NOT NULL,
        emp_last_name VARCHAR(50) NOT NULL,
        emp_role VARCHAR(50) NOT NULL,
        emp_date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>