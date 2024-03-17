<?php
// Directory containing your page files
$pagesDirectory = __DIR__ . '/pages';
$pages = array_diff(scandir($pagesDirectory), [".", ".."]);

// Generate a hash map for routing
$routes = [];
$subRoutes = [];
foreach ($pages as $page) {
    $pageName = pathinfo($page, PATHINFO_FILENAME); // Get file name without extension
    $routes[$pageName] = "/pages/" . $page;
}
$routes["settings"] = "./settings.php";
?>