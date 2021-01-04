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
    if (isset($_SESSION['$user']) || !empty($_SESSION['$user']))
    {
        $errors['session'] = 'Access denied! User is signed in!';

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

    // Validate form
    validate_form();

    // Check if there are any errors
    if (!empty($errors)) 
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    } 
    else 
    {
        $data['success'] = true;
        $data['message'] = 'Login sucessful! Please wait!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */

    // Login user
    function login()
    {
        global $errors;
        global $user;

        // Get user by username and password
        try
        {
            $stmt = $user->select_by_username_password();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if fetched row is empty or not
            if (!empty($row))
            {
                // Check if user is activated/has authority
                if (!empty($row['user_authority']))
                {
                    $_SESSION['$user'] = $row['username'];
                    $_SESSION['$user_id'] = $row['id'];
                    $_SESSION['$user_authority'] = $row['user_authority'];
                }
                else
                {
                    $errors['login'] = 'Your account is not activated.';
                }
            }
            else
            {
                $errors['login'] = 'Invalid username and/or password.';
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

    // Validate form
    function validate_form()
    {
        global $errors;
        global $user; 

        // Prepare fields
        $username = isset($_POST['username']) ? prepare_field($_POST['username']) : null;
        $password = isset($_POST['password']) ? prepare_field($_POST['password']) : null;

        if (empty($username) || !isset($username))
            $errors['username'] = 'Username is required.';

        if (empty($password) || !isset($password))
            $errors['password'] = 'Password is required.';

        // Check errors
        if (empty($errors)){
            $user->username = $_POST['username'];
            $user->password = md5($_POST['password']);

            login();
        }
    }
?>