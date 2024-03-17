<?php
require_once(__DIR__."/../../backend/db.php"); // Include your database connection
if (!isset($_GET["invoiceId"])) {
  header("Location: /?page=invoices&error=Invoice ID is required when managing InvoiceItems0!");
  exit();
}
$invoiceId = $_GET["invoiceId"];
$itemId = isset($_GET["itemId"]) ? (int)$_GET["itemId"] : null;
$item = null;
if($itemId) {
  $stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE id = ? AND invoice_id = ?");
  $stmt->execute([$itemId, $invoiceId]);
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
}
if(!$item) {
  $defaultCurrency = $pdo->query("SELECT value FROM settings WHERE key = 'currency'")->fetchColumn();
  $item = [
    'id' => null,
    'invoice_id' => $invoiceId,
    'description' => null,
    'subdescription' => null,
    'quantity' => 1,
    'price' => 0,
    'currency' => $defaultCurrency
  ];
}
$stmt = $pdo->prepare("SELECT iso_code, name FROM currencies");
$stmt->execute();
$currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pageTitle = $itemId ? "Edit Invoice Item" : "Add New Invoice Item";
?>

<!DOCTYPE html>
<html lang="en">
<?php require(__DIR__."/../../templates/header.php"); ?>
<h1><?= $itemId ? "Edit Invoice" . $invoiceId . " Item" : "Add New Invoice " . $invoiceId . " Item" ?></h1>
<form action="/backend/manage_item.php" method="post">
  <input type="hidden" name="id" value="<?= htmlspecialchars($itemId) ?>">
  <input type="hidden" name="invoice_id" value="<?= htmlspecialchars($invoiceId) ?>">
  <label for="description">Description:</label>
  <input type="text" id="description" name="description" value="<?= htmlspecialchars($item['description']) ?>" required> <br />
  <label for="subdescription">Sub-Description:</label>
  <input type="text" id="subdescription" name="subdescription" value="<?= htmlspecialchars($item['subdescription']) ?>"> <br />
  <label for="quantity">Quantity:</label>
  <input type="snumber" id="quantity" min="0" name="quantity" value="<?= htmlspecialchars($item['quantity']) ?>" required> <br />
  <label for="price">Price:</label>
  <input type="number" id="price" min="0" name="price" value="<?= htmlspecialchars($item['price']) ?>" required> <br />
  <label for="currency">Currency:</label>
  <select id="currency" name="currency" class="choices" placeholder="Select a currency">
    <?php foreach ($currencies as $currency): ?>
        <option value="<?= $currency['iso_code'] ?>" <?= $currency['iso_code'] == $item["currency"] ? 'selected' : '' ?>>
            <?= htmlspecialchars($currency['iso_code']) ?> (<?= $currency['name'] ?>)
        </option>
    <?php endforeach; ?>
  </select>
  <input type="submit" name="add" value="<?= $itemId ? "Update item" : "Add Item" ?>">
  <?php if($itemId): ?>
    <input type="submit" name="delete" value="Delete Item">
  <?php endif; ?>
</form>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var element = document.getElementById('currency');
    var choices = new Choices(element, {
        removeItemButton: true, // Adds a button to remove selected item
        searchEnabled: true, // Enables search functionality
        searchPlaceholderValue: 'Select currency', // Custom search placeholder
        itemSelectText: '', // Text to show for selecting an item
        shouldSort: false, // Disable sorting if your options are pre-ordered
    });
  });
</script>