<?php
require 'db.php'; // Ensure you have the database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $company_id = filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_STRING);
    $vat_number = filter_input(INPUT_POST, 'vat_number', FILTER_SANITIZE_STRING);

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO clients (name, address, federal_id, vat_number) VALUES (:name, :address, :company_id, :vat_number)");
    $stmt->execute([
      ':name' => $name,
      ':address' => $address,
      ':company_id' => $company_id,
      ':vat_number' => $vat_number,

    ]);
    // Redirect back to the list_clients.php or display a success message
    header("Location: /?page=clients"); // Redirect to the clients list
    exit;
}
