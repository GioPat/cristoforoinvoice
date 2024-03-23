<?php
require 'db.php'; // Ensure you have the database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["add"])) {
    $id = filter_input(INPUT_POST, 'clientId', FILTER_UNSAFE_RAW);
    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
    $address = filter_input(INPUT_POST, 'address', FILTER_UNSAFE_RAW);
    $company_id = filter_input(INPUT_POST, 'federal_id', FILTER_UNSAFE_RAW);
    $vat_number = filter_input(INPUT_POST, 'vat_number', FILTER_UNSAFE_RAW);

    if($id == null) {
      $stmt = $pdo->prepare("
        INSERT INTO clients (name, address, federal_id, vat_number)
        VALUES (:name, :address, :company_id, :vat_number)"
      );
      $stmt->execute([
        ':name' => $name,
        ':address' => $address,
        ':company_id' => $company_id,
        ':vat_number' => $vat_number,
      ]);
      $id = $pdo->lastInsertId();
    } else {
      $stmt = $pdo->prepare("
        UPDATE clients
        SET name = :name, address = :address, federal_id = :company_id, vat_number = :vat_number
        WHERE id = :id"
      );
      $stmt->execute([
        ':id' => $id,
        ':name' => $name,
        ':address' => $address,
        ':company_id' => $company_id,
        ':vat_number' => $vat_number,
      ]);
    }
  } elseif (isset($_POST["delete"])) {
    // Validate and sanitize input
    $id = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_STRING);
    if ($id == null) {
      header("Location: /?page=clients&error=ID is required when Deleting!"); // Redirect to the clients list
      exit();
    }
    // Delete from the database
    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = :id");
    $stmt->execute([
      ':id' => $id,
    ]);
  }
  // Redirect back to the list_clients.php or display a success message
  header("Location: /pages/clients.php");
  exit();
}
