<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CristoInvoice</title>
    <link rel="stylesheet" href="http://localhost:8080/public/style.css">
</noscript>
</head>
<body>

<nav>
    <ul>
        <?php foreach ($routes as $name => $path): ?>
            <li><a href="?page=<?= $name ?>"><?= ucfirst($name) ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<!-- Your page content goes here -->

</body>
</html>
