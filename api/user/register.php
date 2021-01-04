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
        $data['message'] = 'Registration successful! Your account will be activated soon!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */
    
    // Check if email address already exists in database
    function check_email($email)
    {
        global $errors;
        global $user;

        $user->email = $email;

        // Get user by email
        try
        {
            $stmt = $user->select_by_email();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($row))
                $errors['email'] = 'Email Address already taken!';
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

    // Check if username already exists in database
    function check_username($username)
    {
        global $errors;
        global $user;

        $user->username = $username;

        // Get user by username
        try
        {
            $stmt = $user->select_by_username();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($row))
                $errors['username'] = 'Username already taken!';
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

    // Preparing e-mail field for database without SQL Injectio
    function prepare_field_email($field)
    {
        return trim(preg_replace("/[;,<>&=%:'“ ]/i", "", $field));
    }

    // Register user
    function register()
    {
        global $errors;
        global $user;

        // Insert user
        try
        {
            $stmt = $user->insert();
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

    // Validate form
    function validate_form()
    {
        global $errors;
        global $user;

        // Prepare fields
        $username = isset($_POST['username']) ? prepare_field($_POST['username']) : null;
        $email = isset($_POST['email']) ? prepare_field_email($_POST['email']) : null;
        $password = isset($_POST['password']) ? prepare_field($_POST['password']) : null;
        $password_confirm = isset($_POST['password_confirm']) ? prepare_field($_POST['password_confirm']) : null;

        if (empty($username) || !isset($username))
            $errors['username'] = 'Username is required.';
        else if (strlen($username) < 5 || strlen($username) > 55)
            $errors['username'] = 'Username must be between 5 and 55 characters.';
        else if (!preg_match("/^[a-zA-Z0-9]+$/", $username))
            $errors['username'] = 'Username must be letters and numbers. No whitespaces allowed!';
        else 
            check_username($username);
        
        if (empty($email) || !isset($email))
            $errors['email'] = 'E-mail address is required.';
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors['email'] = 'E-mail address must be valid.';
        else
            check_email($email);
            

        if (empty($password) || !isset($password))
            $errors['password'] = 'Password is required.';
        else if (!isset($password_confirm))
            $errors['password_confirm'] = 'Confirmed Password is required.';
        else if (strlen($password) < 8 || strlen($password) > 100)
            $errors['password'] = 'Password must be between 8 and 100 characters.';
        else if (!preg_match("/^[a-zA-Z0-9\s]+$/", $password))
            $errors['password'] = 'Password must be letters, numbers and spaces.';
        else if ($password != $password_confirm)
            $errors['password_confirm'] = 'Both passwords must match!';

        // Check errors
        if(empty($errors))
        {
            $user->username = $username;
            $user->email = $email;
            $user->password = md5($password);

            register();
        }
    }
?>