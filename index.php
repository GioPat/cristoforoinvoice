<?php
$url = parse_url($_SERVER['REQUEST_URI']);
print_r($url);
?>
<!DOCTYPE html>
<html lang="en">

<?php require(__DIR__."/templates/header.php"); ?>
<main>
<h1>WELCOME TO CristoforoINVOICE</h1>
<p>Manage your invoices and clients with ease.</p>
<img
    style="display: block; margin-left: auto; margin-right: auto; clip-path: circle(150px at center);"
    src="/public/img/cristoforo_invoice_logo.webp" alt="CristoforoINVOICE logo" width="400" height="400">
</main>