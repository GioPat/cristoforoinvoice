<?php
require_once(__DIR__."/../../backend/db.php"); // Include your database connection

$invoiceId = isset($_GET["invoiceId"]) ? (int)$_GET["invoiceId"] : null;
$invoice = null;
$items = [];
// Check if we're editing an existing invoice
if ($invoiceId) {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
    $items = $pdo->query("SELECT * FROM invoice_items WHERE invoice_id = $invoiceId")->fetchAll(PDO::FETCH_ASSOC);
    $vat = $pdo->query("SELECT value FROM settings WHERE key = 'vat'")->fetchColumn() ?? 0;
    $subTotal = array_sum(array_column($items, "price"));
    $afterDiscount = $subTotal - ($subTotal * $invoice['discount']);
    $vatAmount = $afterDiscount * $vat;
    $total = $afterDiscount + $vatAmount;
    $currency = $items[0]["currency"];
}
// Set defaults for a new invoice if not editing
if (!$invoice) {
    $invoice = [
        'id' => null,
        'client_id' => null,
        'date' => date('Y-m-d'), // Today's date
        'due_date' => date('Y-m-d', strtotime('+30 days')) // 30 days from today
    ];
}
$clients = $pdo->query("SELECT id, name FROM clients")->fetchAll(PDO::FETCH_ASSOC);
$pageTitle = $invoiceId ? "Edit Invoice" : "Add New Invoice";
?>

<!DOCTYPE html>
<html lang="en">
<?php require(__DIR__."/../../templates/header.php"); ?>

<h1><?= $invoiceId ? "Edit Invoice" : "Add New Invoice" ?></h1>
<button id="toggleFormButton" >Hide Invoice details</button>
<form id="invoiceForm" action="save_invoice.php" method="post">
    <input type="hidden" name="invoiceId" value="<?= htmlspecialchars($invoice['id']) ?>">

    <label for="client_id">Client:</label>
    <select id="client_id" name="client_id" class="choices" placeholder="Select a client">
        <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>" <?= $client['id'] == $invoice["client_id"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($client['name']) ?> (ID: <?= $client['id'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label for="invoiceDate">Date:</label>
    <input type="date" id="invoiceDate" name="invoiceDate" value="<?= htmlspecialchars($invoice['issue_date']) ?>" required>

    <label for="dueDate">Due Date:</label>
    <input type="date" id="dueDate" name="dueDate" value="<?= htmlspecialchars($invoice['due_date']) ?>" required>
    <br />
    <label for="discount">Discount:</label>
    <input type="numer" id="discount" name="discount" min="0" max="1" value="<?= htmlspecialchars($invoice['discount']) ?>">
    <br />
    <!-- Add more fields as needed -->

    <input type="submit" name="add" value="<?= $invoiceId ? "Update Invoice" : "Add Invoice" ?>">
    <?php if($invoiceId): ?>
    <input type="submit" name="delete" value="Delete Invoice">
    <?php endif; ?>
</form>
<div class="info-block">
    <div class="info-item">
        <div class="info-title">Sub-total</div>
        <div class="info-value"><?= $subTotal . $currency ?></div>
    </div>
    <div class="info-item">
        <div class="info-title">After Discount</div>
        <div class="info-value"><?= $afterDiscount . $currency ?></div>
    </div>
    <div class="info-item">
        <div class="info-title">Grand Total (After VAT)</div>
        <div class="info-value"><?= $total . $currency ?></div>
    </div>
</div>
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Sub description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Currency</th>
            <!-- Add columns as needed -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td><?= htmlspecialchars($item['subdescription']) ?></td>
            <td><?= htmlspecialchars($item['quantity']) ?></td>
            <td><?= htmlspecialchars($item['price']) ?></td>
            <td><?= htmlspecialchars($item['currency']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<button onclick="window.location='add_item.php?invoice_id=<?= $invoiceId ?>'">Add Item</button>
<button onclick="window.location='export_invoice.php?invoice_id=<?= $invoiceId ?>'">Export PDF</button>

<!-- Optionally, add a section here to manage invoice items if editing -->
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var element = document.getElementById('client_id');
        var choices = new Choices(element, {
            removeItemButton: true, // Adds a button to remove selected item
            searchEnabled: true, // Enables search functionality
            searchPlaceholderValue: 'Search clients...', // Custom search placeholder
            itemSelectText: '', // Text to show for selecting an item
            shouldSort: false, // Disable sorting if your options are pre-ordered
        });
        const toggleButton = document.getElementById('toggleFormButton');
        let form = document.getElementById('invoiceForm');

        toggleButton.addEventListener('click', function() {
            if (form.style.display === "none") {
                form.style.display = "block";
                toggleButton.textContent = "Hide Invoice details";
            } else {
                form.style.display = "none";
                toggleButton.textContent = "Show Invoice details";
            }
        });
    });
    const form = document.getElementById('invoiceForm');
    form.style.display = "none";
    const toggleButton = document.getElementById('toggleFormButton');
    toggleButton.textContent = "Show Invoice details";

</script>
</html>
