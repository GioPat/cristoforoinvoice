<?php
// button_component.php

function renderButton($url, $text) {
    // Output the button HTML
    echo "<button class='button' onclick=\"location.href='{$url}'\">{$text}</button>";
}
?>
