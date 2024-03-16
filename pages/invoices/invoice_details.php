<?php
require 'db.php'; // Include your database connection

$invoiceId = isset($_GET['invoiceId']) ? (int)$_GET['invoiceId'] : null;
$invoice = null;

// Check if we're editing an existing invoice
if ($invoiceId) {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Set defaults for a new invoice if not editing
if (!$invoice) {
    $invoice = [
        'id' => null,
        'client_name' => '',
        'date' => date('Y-m-d'), // Today's date
        'due_date' => '', // Default due date or calculation
        'total' => 0,
        // Add other fields as necessary
    ];
}
$pageTitle = $invoiceId ? "Edit Invoice" : "Add New Invoice";
?>

<!DOCTYPE html>
<html lang="en">
<?php include "templates/header.php" ?>

<h2><?= $invoiceId ? "Edit Invoice" : "Add New Invoice" ?></h2>

<form action="save_invoice.php" method="post">
    <input type="hidden" name="invoiceId" value="<?= htmlspecialchars($invoice['id']) ?>">

    <label for="clientName">Client Name:</label>
    <input type="text" id="clientName" name="clientName" value="<?= htmlspecialchars($invoice['client_name']) ?>" required>

    <label for="invoiceDate">Date:</label>
    <input type="date" id="invoiceDate" name="invoiceDate" value="<?= htmlspecialchars($invoice['date']) ?>" required>

    <label for="dueDate">Due Date:</label>
    <input type="date" id="dueDate" name="dueDate" value="<?= htmlspecialchars($invoice['due_date']) ?>" required>

    <!-- Add more fields as needed -->

    <input type="submit" value="<?= $invoiceId ? "Update Invoice" : "Add Invoice" ?>">
</form>

<!-- Optionally, add a section here to manage invoice items if editing -->

</body>
</html>
