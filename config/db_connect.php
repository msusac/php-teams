<?php
    $connection = mysqli_connect('localhost', 'root', '', 'php_teams_db');

    if(!$connection){
        die("Connection error: " . mysqli_connect_error());
    }
?>