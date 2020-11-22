<?php
    //Check if access is from AJAX/JS Scripts
    include('../../config/ajax_connect.php');

    //Session management 
    session_start();
    session_unset();
    session_destroy();
    
    //Initialize data field
    $data['success'] = true;
    $data['message'] = 'Logout sucessful! Please wait!';

    //Return all data to an AJAX call
    echo json_encode($data);
?>
