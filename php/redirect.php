<?php

if (!isset($_SERVER['HTTPS'])) {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $location);
    die();  
}
?>