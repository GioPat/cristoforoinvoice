<!DOCTYPE html>
<html lang="en">
<?php include "templates/header.php" ?>
<body>
	<h1>Invoices</h1>
    <form action="generate_invoice.php" method="post">
        <input type="text" name="clientName" placeholder="Client Name" required>
        <input type="text" name="clientAddress" placeholder="Client Address" required>
        <!-- Add more fields as necessary -->
        <button type="submit">Generate Invoice</button>
    </form>
</body>
</html>
