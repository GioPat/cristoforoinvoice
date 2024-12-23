<?php
// db.php

// Define the absolute path to your database file
$dbConnString = $_ENV["DATABASE_CONN_STRING"] ?? "sqlite:" . dirname(__DIR__) . '/database/db.sqlite';

try {
    $pdo = new PDO($dbConnString);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
?>