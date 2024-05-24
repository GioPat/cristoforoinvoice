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
    <link rel="stylesheet" href="/public/css/tailwind.css"></script>

    <script src="/public/js/choices.min.js"></script>
</noscript>
</head>
<body class="flex justify-center items-center grid grid-cols-2">
<?php
$errorMsg = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : null;
?>
<?php if (!is_null($errorMsg)): ?>
<div id="errorPopup" class="error-popup">
    <div class="error-content">
        <span onclick="onPopupCloseClick()" class="close">&times;</span>
        <p><?= $errorMsg ?></p>
    </div>
</div>
<script type="text/javascript">
    const onPopupCloseClick = () => {
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
<aside class="flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-white border-r rtl:border-r-0 rtl:border-l dark:bg-gray-900 dark:border-gray-700">    <nav class="flex flex-col flex-1 space-y-6">
    <a href="#">
        <img class="w-auto h-6 " src="/public/img/logo_for_dark.svg" alt="Application Logo">
    </a>
    <button
        class="flex flex-col flex1 space-y-2 text-white"
        id="menuButton"
        onclick="toggleMenu()"
    >OPEN/CLOSE MENu</button>
    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav>
            <?php foreach ($routes as $name => $path): ?>
                <a href="<?= $path ?>" class="flex items-center px-4 py-2 mt-5 text-gray-600 transition-colors duration-300 transform rounded-md dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200 hover:text-gray-700" href="#">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg> -->
                    <span class="mx-4 font-medium"><?= ucfirst($name); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>
</aside>
<!-- Your page content goes here -->

</body>
</html>
