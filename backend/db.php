<?php
// db.php

// Define the absolute path to your database file
$dbConnString = $_ENV["DATABASE_CONN_STRING"] ?? "sqlite:" . dirname(__DIR__) . '/db.sqlite';

try {
    $pdo = new PDO($dbConnString);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS clients (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL,
		vat_number TEXT,
		federal_id TEXT NOT NULL,
        address TEXT NOT NULL,
        UNIQUE(name)
        -- add other fields as needed
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY,
        key TEXT NOT NULL,
        value TEXT NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        id INTEGER PRIMARY KEY,
        key TEXT NOT NULL,
        value TEXT NOT NULL
    )");


    $pdo->exec("CREATE TABLE IF NOT EXISTS invoices (
        id INTEGER PRIMARY KEY,
        invoice_number TEXT NOT NULL,
        client_id INTEGER NOT NULL,
        issue_date TEXT NOT NULL,
        due_date TEXT NOT NULL,
        notes TEXT,
        discount REAL,
        FOREIGN KEY (client_id) REFERENCES clients(id)
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS invoice_items (
        id INTEGER PRIMARY KEY,
        invoice_id INTEGER NOT NULL,
        description TEXT NOT NULL,
        subdescription TEXT,
        quantity INTEGER NOT NULL,
        price REAL NOT NULL,
        currency TEXT NOT NULL,
        FOREIGN KEY (invoice_id) REFERENCES invoice(id)
    )");
    // You can add more table creation statements here
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
?>