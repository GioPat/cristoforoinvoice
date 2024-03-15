<?php
// Directory containing your page files
$pagesDirectory = './pages';
$pages = array_diff(scandir($pagesDirectory), array('..', '.')); // Remove '.' and '..'

// Generate a hash map for routing
$routes = [];
foreach ($pages as $page) {
    $pageName = pathinfo($page, PATHINFO_FILENAME); // Get file name without extension
    $routes[$pageName] = $pagesDirectory . '/' . $page;
}
$routes["settings"] = "./settings.php";
?>