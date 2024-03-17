<?php
// Include your database connection file
require_once("../backend/db.php"); // Adjust this path as needed

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
<button id="toggleFormButton">Hide Form</button>
<form id="clientForm" action="backend/manage_client.php" method="post">
    <label for="name">ID:</label>
    <input type="text" id="id" name="id"><br />
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br />
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required><br />
    <label for="company_id">Company ID:</label>
    <input type="text" id="company_id" name="company_id" required><br />
    <label for="vat_number">VAT Number:</label>
    <input type="text" id="vat_number" name="vat_number"><br />
    <input type="submit" name="add" value="Add Client">
    <input type="submit" name="update" value="Update Client">
    <input type="submit" name="delete" value="Delete Client">
</form>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Company ID</th>
            <th>VAT number</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['id']) ?></td>
                <td><?= htmlspecialchars($client['name']) ?></td>
                <td><?= htmlspecialchars($client['address']) ?></td>
                <td><?= htmlspecialchars($client['federal_id']) ?></td>
                <td><?= htmlspecialchars($client['address']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>

<script>
const form = document.getElementById('clientForm');
form.style.display = "none";
const toggleButton = document.getElementById('toggleFormButton');
toggleButton.textContent = "Show Form";

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

</html>
