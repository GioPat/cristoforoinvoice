<?php
    require_once(__DIR__."/../../backend/db.php");
    require_once(__DIR__."/../../components/button.php");
    $clientId = isset($_GET["clientId"]) ? (int)$_GET["clientId"] : null;
    $client = null;
    $invoices = [];
    if ($clientId) {
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$clientId]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->query("
            SELECT i.*, (i.due_date < DATE('now') AND NOT payed) as is_overdue, c.name as client_name
            FROM invoices AS i LEFT JOIN clients AS c ON i.client_id = c.id
            WHERE client_id = $clientId ORDER BY issue_date DESC");
        $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Set defaults for a new invoice if not editing
    if (!$client) {
        $client = [
            'id' => null,
            'name' => null,
            'address' => null,
            'federal_id' => null,
            'vat_number' => null
        ];

    }
    $pageTitle = $clientId ? "Edit Client: " . $client['name'] : "Add New Client";
?>

<!DOCTYPE html>
<html lang="en">
<?php require(__DIR__."/../../templates/header.php"); ?>

<h1><?= $clientId ? "Edit Client " . $client['name'] : "Add New Client" ?></h1>
<button class="button" id="toggleFormButton" >Hide details</button>
<form id="clientForm" action="/backend/manage_client.php" method="post">
    <input type="hidden" name="clientId" value="<?= ($client['id']) ? htmlspecialchars($client['id']) : null ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?= $client['name'] ? htmlspecialchars($client['name']) : null?>" required><br />
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" value="<?= $client['address'] ? htmlspecialchars($client['address']) : null ?>" required><br />
    <label for="company_id">Company ID:</label>
    <input type="text" id="federal_id" name="federal_id" value="<?= $client['federal_id'] ? htmlspecialchars($client['federal_id']) : null ?>" required><br />
    <label for="vat_number">VAT Number:</label>
    <input type="text" id="vat_number"  value="<?= $client['vat_number'] ? htmlspecialchars($client['vat_number']) : null ?>" name="vat_number"><br />
    <input type="submit" name="add" value="<?= $clientId ? "Update Client" : "Add Client" ?>">
    <?php if($clientId): ?>
    <input type="submit" onclick="return confirm('Are you sure you want to delete the client?')" name="delete" value="Delete Client">
    <?php endif; ?>
</form>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Invoice Number</th>
            <th>Client Name</th>
            <th>Issue Date</th>
            <th>Due Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoices as $invoice): ?>
        <tr class="<?= $invoice['is_overdue'] ? 'overdue' : '' ?>">
            <td><?= htmlspecialchars($invoice['id']) ?></td>
            <td><?= htmlspecialchars($invoice['invoice_number']) ?></td>
            <td><?= htmlspecialchars($invoice['client_name']) ?></td>
            <td><?= htmlspecialchars($invoice['issue_date']) ?></td>
            <td><?= htmlspecialchars($invoice['due_date']) ?></td>
            <td>
                <?= renderButton("/pages/invoices/manage_invoice.php?invoiceId=" . $invoice['id'], "Manage") ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
const toggleButton = document.getElementById('toggleFormButton');

document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFormButton');
    let form = document.getElementById('clientForm');

    toggleButton.addEventListener('click', function() {
        if (form.style.display === "none") {
            form.style.display = "block";
            toggleButton.textContent = "Hide Form";
        } else {
            form.style.display = "none";
            toggleButton.textContent = "Show Form";
        }
    });
});
</script>