<?php
    // Check if access is from AJAX/JS Scripts
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');


    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    if (!IS_AJAX) {
        header("Location: /php-teams");
    }

    $pos = strpos($_SERVER['HTTP_REFERER'], getenv('HTTP_HOST'));
    if ($pos === false)
        header("Location: /php-teams");
?>
