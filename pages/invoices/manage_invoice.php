<?php
require_once(__DIR__."/../../backend/db.php");
require_once(__DIR__."/../../components/button.php");

$invoiceId = isset($_GET["invoiceId"]) ? (int)$_GET["invoiceId"] : null;
$invoice = null;
$items = [];
$currency = null;
// Check if we're editing an existing invoice
if ($invoiceId) {
    $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ?");
    $stmt->execute([$invoiceId]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
    $items = $pdo->query("SELECT i.*, price * quantity as total_price FROM invoice_items as i WHERE invoice_id = $invoiceId")->fetchAll(PDO::FETCH_ASSOC);
    $vat = $pdo->query("SELECT value FROM settings WHERE key = 'vat'")->fetchColumn() ?? 0;
    $subTotal = array_sum(array_column($items, "total_price"));
    $afterDiscount = $subTotal - ($subTotal * $invoice['discount']);
    $vatAmount = $afterDiscount * $vat;
    $total = $afterDiscount + $vatAmount;
    if (count($items) > 0) {
        $currency = $items[0]["currency"];
    }
}
// Set defaults for a new invoice if not editing
if (!$invoice) {
    $invoice = [
        'id' => null,
        'client_id' => null,
        'discount' => 0,
        'notes' => null,
        'po_reference' => null,
        'issue_date' => date('Y-m-d'), // Today's date
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
<button class="button" id="toggleFormButton" >Hide Invoice details</button>
<form id="invoiceForm" action="/backend/manage_invoice.php" method="post">
    <input type="hidden" name="invoiceId" value="<?= $invoice['id'] ? htmlspecialchars($invoice['id']) : null ?>">

    <label for="client_id">Client:</label>
    <select id="client_id" name="client_id" class="choices" placeholder="Select a client">
        <?php foreach ($clients as $client): ?>
            <option value="<?= $client['id'] ?>" <?= $client['id'] == $invoice["client_id"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($client['name']) ?> (ID: <?= $client['id'] ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <label for="issue_date">Date:</label>
    <input type="date" id="issue_date" name="issue_date" value="<?= $invoice['issue_date'] ? htmlspecialchars($invoice['issue_date']) : null ?>" required>

    <label for="po_reference">Purchase Order / Contract reference:</label>
    <input type="po_reference" id="po_reference" name="po_reference" value="<?= $invoice['po_reference'] ? htmlspecialchars($invoice['po_reference']) : null ?>" required>

    <label for="due_date">Due Date:</label>
    <input type="date" id="due_date" name="due_date" value="<?= $invoice['due_date'] ? htmlspecialchars($invoice['due_date']) : null ?>" required>
    <br />
    <label for="discount">Discount:</label>
    <input type="numer" id="discount" name="discount" min="0" max="1" value="<?= $invoice['discount'] ? htmlspecialchars($invoice['discount']) : 0 ?>">
    <br />
    <label for="notes">Notes:</label>
    <input type="text" id="notes" name="notes" value="<?= $invoice['notes'] ? htmlspecialchars($invoice['notes']) : null ?>">
    <br />
    <!-- Add more fields as needed -->

    <input type="submit" name="add" value="<?= $invoiceId ? "Update Invoice" : "Add Invoice" ?>">
    <?php if($invoiceId): ?>
    <input type="submit" onclick="return confirm('Are you sure you want to delete the invoice?')" name="delete" value="Delete Invoice">
    <?php endif; ?>
</form>
<?php if($invoiceId): ?>
<div class="info-block">
    <div class="info-item">
        <div class="info-title">Sub-total</div>
        <div class="info-value"><?= ($currency) ? $subTotal . $currency : "-"?></div>
    </div>
    <div class="info-item">
        <div class="info-title">After Discount</div>
        <div class="info-value"><?= ($currency) ? $afterDiscount . $currency : "-" ?></div>
    </div>
    <div class="info-item">
        <div class="info-title">Grand Total (After VAT)</div>
        <div class="info-value"><?= ($currency) ? $total . $currency : "-" ?></div>
    </div>
</div>
<?php endif; ?>
<table style="margin-bottom: 1rem;">
    <thead>
        <tr>
            <th>Description</th>
            <th>Sub description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Currency</th>
            <th>Actions</th>
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
            <td>
                <?= renderButton("/pages/invoices/manage_item.php?invoiceId=" . $invoiceId . "&itemId=" . $item['id'], "Manage") ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php if($invoiceId): ?>
    <?= renderButton("/pages/invoices/manage_item.php?invoiceId=" . $invoiceId, "Add Item"); ?>
    <?= renderbutton("/backend/export_invoice.php?invoiceId=" . $invoiceId, "Export PDF", "_blank"); ?>
<?php endif; ?>
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
    const urlParams = new URLSearchParams(window.location.search);
    const invoiceId = urlParams.get('invoiceId');
    console.log(invoiceId);
    if (invoiceId === null) {
        const form = document.getElementById('invoiceForm');
        form.style.display = "block";
        const toggleButton = document.getElementById('toggleFormButton');
        toggleButton.textContent = "Hide Invoice details";
    } else {
        const form = document.getElementById('invoiceForm');
        form.style.display = "none";
        const toggleButton = document.getElementById('toggleFormButton');
        toggleButton.textContent = "Show Invoice details";
    }
</script>
</html>
