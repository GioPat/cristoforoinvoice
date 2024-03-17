<?php
// button_component.php

function renderButton($url, $text, $target = "_self") {
    // Output the button HTML
    echo "<a href='${url}' target='${target}'><button class='button'\">{$text}</button></a>";
}
?>
