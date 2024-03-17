<?php
    $pageTitle = $pageTitle ?? "Home";
    require(__DIR__."/../routes.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CristoforoInvoice - <?= $pageTitle ?></title>
    <link rel="icon" type="image/x-icon" href="/public/img/favicon-for-light.ico" media="(prefers-color-scheme: light)">
    <link rel="icon" type="image/x-icon" href="/public/img/favicon-for-dark.ico" media="(prefers-color-scheme: dark)">

    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/choices.min.css">
    <script src="/public/js/choices.min.js"></script>
</noscript>
</head>
<body>
<?php
$errorMsg = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
?>

<?php if (!is_null($errorMsg)): ?>
<div id="errorPopup" class="error-popup">
    <div class="error-content">
        <span onclick="onClick()" class="close">&times;</span>
        <p><?= $errorMsg ?></p>
    </div>
</div>
<script>
    const onClick = () => {
        const popUp = document.getElementById('errorPopup');
        popUp.style.display = "none";
        const queryParams = new URLSearchParams(window.location.search);
        queryParams.delete("error");
        const newUrl = window.location.origin
          + window.location.pathname
          + "?" + queryParams.toString();
        window.history.pushState({path:newUrl}, "", newUrl);
    }
    window.onload = function() {
        document.getElementById("errorPopup").style.display = "block";
    };
</script>
<?php endif; ?>
<nav>
    <ul>
        <?php foreach ($routes as $name => $path): ?>
            <li><a href="<?= $path ?>"><?= ucfirst($name) ?></a></li>
        <?php endforeach; ?>
    </ul>
</nav>
<!-- Your page content goes here -->

</body>
</html>
