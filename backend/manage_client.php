<?php
require 'db.php'; // Ensure you have the database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["add"])) {
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
  } elseif (isset($_POST["update"])) {
    // Validate and sanitize input
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    if ($id === null) {
      // Redirect back to the list_clients.php or display a success message
      header("Location: /?page=clients&error=ID is required"); // Redirect to the clients list
      exit();
    }
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $company_id = filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_STRING);
    $vat_number = filter_input(INPUT_POST, 'vat_number', FILTER_SANITIZE_STRING);

    // Update the database
    $stmt = $pdo->prepare("UPDATE clients SET name = :name, address = :address, federal_id = :company_id, vat_number = :vat_number WHERE id = :id");
    $stmt->execute([
      ':id' => $id,
      ':name' => $name,
      ':address' => $address,
      ':company_id' => $company_id,
      ':vat_number' => $vat_number,
    ]);
  } elseif (isset($_POST["delete"])) {
    // Validate and sanitize input
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

    // Delete from the database
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = :id");
    $stmt->execute([
      ':id' => $id,
    ]);
  }
  // Redirect back to the list_clients.php or display a success message
  header("Location: /?page=clients"); // Redirect to the clients list
  exit();
}
