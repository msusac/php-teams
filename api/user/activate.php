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

    // Check user access
    if (!isset($_SESSION['$user']) || empty($_SESSION['$user']) || $_SESSION['$user_authority'] != 'ROLE_ADMIN')
    {
        $errors['session'] = 'Access denied! Admin users only!';

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

    // Check request
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
        check_user();
    else
        $errors['general'] = "Invalid request!";

    // Check if there are any errors
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } 
    else {
        $data['success'] = true;
        $data['message'] = 'User successfully activated!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */

    // Activate user
    function activate_user()
    {
        global $errors;
        global $user;

        // Insert user authority
        try
        {
            $stmt = $user->insert_authority_user();
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

    // Check if user exists
    function check_user()
    {
        global $errors;
        global $user;

        // Set param
        $user->id = $_GET['id'];

        // Get user by Username that's not activated
        try
        {
            $stmt = $user->select_by_id_authority_null();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if fetched row is empty or not
            if (!empty($row))
            {
                activate_user();
            }
            else
            {
                $errors['sql'] = 'Selected user is already activated or does not exists!';
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

    // Preparing fields for database without SQL Injection
    function prepare_field($field)
    {
        return trim(preg_replace("/[;,<>&=%:'“ .]/i", "", $field));
    }
?>