<?php
// Directory containing your page files
$pagesDirectory = './pages';
$pages = array_diff(scandir($pagesDirectory), ["..", "."]); // Remove '.' and '..'

// Generate a hash map for routing
$routes = [];
$subRoutes = [];
foreach ($pages as $page) {
    $fullPath = $pagesDirectory. DIRECTORY_SEPARATOR .$page;
    if (is_dir($fullPath)) {
        $subPages = array_diff(scandir($fullPath), ["..", "."]); // Remove '.' and '..'
        foreach ($subPages as $subPage) {
            $subPageName = pathinfo($subPage, PATHINFO_FILENAME); // Get file name without extension
            $subRoutes[$subPageName] = $page . '/' . $subPage;
        }
        continue;
    }
    $pageName = pathinfo($page, PATHINFO_FILENAME); // Get file name without extension
    $routes[$pageName] = $pagesDirectory . '/' . $page;
}
$routes["settings"] = "./settings.php";
?>