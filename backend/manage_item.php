<?php
require 'db.php'; // Ensure you have the database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $invoiceId = filter_input(INPUT_POST, 'invoice_id', FILTER_SANITIZE_STRING);
  if (isset($_POST["add"])) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $subdescription = filter_input(INPUT_POST, 'subdescription', FILTER_SANITIZE_STRING);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING);
    $currency = filter_input(INPUT_POST, 'currency', FILTER_SANITIZE_STRING);
    if ($id == null) {
      $stmt = $pdo->prepare("
        INSERT INTO invoice_items (invoice_id, description, subdescription, quantity, price, currency)
        VALUES (:invoice_id, :description, :subdescription, :quantity, :price, :currency)"
      );
      $stmt->execute([
        ':invoice_id' => $invoiceId,
        ':description' => $description,
        ':subdescription' => $subdescription,
        ':quantity' => $quantity,
        ':price' => $price,
        ':currency' => $currency,
      ]);
    } else {
      $stmt = $pdo->prepare("
        UPDATE invoice_items
        SET description = :description, subdescription = :subdescription, quantity = :quantity, price = :price, currency = :currency
        WHERE id = :id"
      );
      $stmt->execute([
        ':id' => $id,
        ':description' => $description,
        ':subdescription' => $subdescription,
        ':quantity' => $quantity,
        ':price' => $price,
        ':currency' => $currency,
      ]);
    }
    header("Location: /pages/invoices/manage_invoice.php?invoiceId=" . $invoiceId);
    exit();
  } elseif (isset($_POST["delete"])) {
    // Validate and sanitize input
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
    if ($id == null) {
      header("Location: /pages/manage_invoice.php?invoiceId=" . $invoiceId . "error=ID is required when Deleting!"); // Redirect to the clients list
      exit();
    }
    // Delete from the database
    $stmt = $pdo->prepare("DELETE FROM invoice_items WHERE id = :id");
    $stmt->execute([
      ':id' => $id,
    ]);
  }
  header("Location: /pages/invoices/manage_invoice.php?invoiceId=" . $invoiceId);
  exit();
}
