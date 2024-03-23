<?php
// Include your database connection file
require_once("../backend/db.php"); // Adjust this path as needed
require_once(__DIR__."/../components/button.php");

try {
    $stmt = $pdo->query("SELECT * FROM clients");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require(__DIR__."/../templates/header.php"); ?>

<h1>Clients</h1>
<?= renderButton("/pages/clients/manage_client.php", "Create new Client") ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Company ID</th>
            <th>VAT number</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['id']) ?></td>
                <td><?= htmlspecialchars($client['name']) ?></td>
                <td><?= htmlspecialchars($client['address']) ?></td>
                <td><?= htmlspecialchars($client['federal_id']) ?></td>
                <td><?= htmlspecialchars($client['vat_number']) ?></td>
                <td>
                    <?= renderButton("/pages/clients/manage_client.php?clientId=" . $client['id'], "Manage") ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
