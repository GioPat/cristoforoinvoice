<?php
include_once "routes.php";

// Assuming $routes is defined as above
$page = $_GET['page'] ?? 'home'; // Default to 'home' if no page is specified

if (array_key_exists($page, $routes) && file_exists($routes[$page])) {
    require $routes[$page];
} else {
    echo "Page not found."; // Or include a default 404 page
}

?>