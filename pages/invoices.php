<?php
// Include your database connection file
require_once(__DIR__."/../backend/db.php");
require_once(__DIR__."/../components/button.php");
try {
    $year_stmt = $pdo->query("SELECT value FROM settings WHERE key = 'year'");
    $year = $year_stmt->fetchColumn();
    if (!$year) {
        $year = date('Y');
    }
    $stmt = $pdo->query("
        SELECT i.id, c.name as client_name, i.issue_date, i.invoice_number, i.due_date, (i.due_date < DATE('now') AND NOT payed) as is_overdue
        FROM (
            SELECT * FROM invoices WHERE substr(issue_date, 1, 4) = '$year'
        ) as i left join clients as c ON i.client_id = c.id
        ORDER BY invoice_number DESC, issue_date DESC
    ");
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require(__DIR__."/../templates/header.php"); ?>
<body>

<h1>Invoices</h1>
<button><a href="/pages/invoices/manage_invoice.php">Add New Invoice</a></button>
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

</body>
</html>
