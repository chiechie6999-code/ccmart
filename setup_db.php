<?php
require_once 'php/db_connect.php';

try {
    $sql = file_get_contents('database.sql');
    $pdo->exec($sql);
    echo "Database and tables created successfully.";
} catch (PDOException $e) {
    die("Error creating database: " . $e->getMessage());
}
?>