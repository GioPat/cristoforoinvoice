<?php
require 'db.php'; // Ensure you have the database connection

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["add"])) {
    $id = filter_input(INPUT_POST, 'invoiceId', FILTER_SANITIZE_STRING);
    $issueDate = filter_input(INPUT_POST, 'issue_date', FILTER_SANITIZE_STRING);
    $year = date('Y', strtotime($issueDate));
    $invoiceNumber = (($pdo->query("SELECT MAX(invoice_number) FROM invoices WHERE substr(issue_date, 1, 4) = '$year'")->fetchColumn()) ?? 0) + 1;
    $client_id = filter_input(INPUT_POST, 'client_id', FILTER_SANITIZE_STRING);
    $issueDate = filter_input(INPUT_POST, 'issue_date', FILTER_SANITIZE_STRING);
    $dueDate = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_STRING);
    $discount = filter_input(INPUT_POST, 'discount', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);

    if($id == null) {
      $stmt = $pdo->prepare("
        INSERT INTO invoices (invoice_number, client_id, issue_date, due_date, notes, discount)
        VALUES (:invoice_number, :client_id, :issue_date, :due_date, :notes, :discount)"
      );
      $stmt->execute([
        ':invoice_number' => $invoiceNumber,
        ':client_id' => $client_id,
        ':issue_date' => $issueDate,
        ':due_date' => $dueDate,
        ':notes' => $notes,
        ':discount' => $discount,
      ]);
      $id = $pdo->lastInsertId();
    } else {
      $stmt = $pdo->prepare("
        UPDATE invoices
        SET client_id = :client_id, issue_date = :issue_date, due_date = :due_date, notes = :notes, discount = :discount
        WHERE id = :id"
      );
      $stmt->execute([
        ':id' => $id,
        ':client_id' => $client_id,
        ':issue_date' => $issueDate,
        ':due_date' => $dueDate,
        ':notes' => $notes,
        ':discount' => $discount,
      ]);
    }

    header("Location: /pages/invoices/manage_invoice.php?invoiceId=" . $id);
    exit();
  } elseif (isset($_POST["delete"])) {
    // Validate and sanitize input
    $id = filter_input(INPUT_POST, 'invoiceId', FILTER_SANITIZE_STRING);
    if ($id == null) {
      header("Location: /pages/invoices.php&error=ID is required when Deleting!"); // Redirect to the clients list
      exit();
    }
    // Delete from the database
    $stmt = $pdo->prepare("DELETE FROM invoices WHERE id = :id");
    $stmt->execute([
      ':id' => $id,
    ]);
  }
  header("Location: /pages/invoices.php");
  exit();
}
