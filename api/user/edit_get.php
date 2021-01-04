<?php
    // Start session
    ob_start();
    session_start();

    include_once '../../config/ajax_connect.php';
    include_once '../../config/db_connect.php';
    include_once '../../models/User.php';

    // Initialize data and error arrays
    $data = array();
    $errors = array();

    // Check if user is signed in
    if (!isset($_SESSION['$user']) || empty($_SESSION['$user']))
    {
        $errors['session'] = 'Access denied! User is not signed in!';

        $data['success'] = false;
        $data['errors']  = $errors;

        echo json_encode($data);
        exit();
    }
    
    // Initialize Database Connection
    $dbc = new DatabaseConnection();
    $dbc = $dbc->connect();

    // Initialize User object
    $user = new User($dbc);

    edit_get();

    // Check if there are any errors
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } 
    else {
        $data['success'] = true;
        $data['message'] = 'Successfully retrieved user data!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */

    // Get user fullname for edit
    function edit_get()
    {
        global $errors;
        global $data;
        global $user;

        // Set param
        $user->id = $_SESSION['$user_id'];

        // Get user by username and password
        try
        {
            $stmt = $user->select_fullname_by_id();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if fetched row is empty or not
            if (!empty($row))
            {
                $data['fullname'] = $row['fullname'];
            }
            else
            {
                $errors['sql'] = 'Selected user does not exists!';
            }
        }
        catch (PDOException $e)
        {
            $errors['sql'] = 'DataBase Error: ' . $e->getMessage();
        }
        catch (Exception $e)
        {
            $errors['general'] = 'General Error: ' . $e->getMessage();
        }
    }
?>