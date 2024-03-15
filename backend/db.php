<?php
// db.php
$projectRootDir = dirname(__DIR__); // This goes up one directory from the current 'backend' directory

// Define the absolute path to your database file
$databasePath = $projectRootDir . '/db.sqlite';

try {
    $pdo = new PDO("sqlite:" . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS clients (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
		vat_number TEXT,
		federal_id TEXT NOT NULL,
        address TEXT NOT NULL
        -- add other fields as needed
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY,
        key TEXT NOT NULL,
        value TEXT NOT NULL
    )");

    // You can add more table creation statements here
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
?>