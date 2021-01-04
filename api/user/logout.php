<?php
    // Start session
    ob_start();
    session_start();

    include_once '../../config/ajax_connect.php';

    // Initialize data and error arrays
    $data = array();
    $errors = array();

    // Check if user is signed in
    if (isset($_SESSION['$user']) || !empty($_SESSION['$user']))
    {
        // Destroy session
        session_unset();
        session_destroy();
    
        $data['success'] = true;
        $data['message'] = 'Logout sucessful! Please wait!';
    }
    else
    {
        $errors['session'] = 'Access denied!';

        $data['success'] = false;
        $data['errors']  = $errors;
    }

    // Return all data to an AJAX call
    echo json_encode($data);
?>