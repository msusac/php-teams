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
        $data['user'] = $_SESSION['$user_id'];
        $data['success'] = true;
        $data['message'] = 'Profile updated!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */

    // Check if password is correct
    function check_password($password_old)
    {
        global $errors;
        global $user;

        // Prepare fields
        $user->username = $_SESSION['$user'];
        $user->password = md5($password_old);

        // Get user by username and password
        try
        {
            $stmt = $user->select_by_username_password();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if fetched row is empty or not
            if (empty($row))
                $errors['password_old'] = 'Wrong old password.';
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

    // Preparing fullname field for database without SQL Injection
    function prepare_field_fullname($field)
    {
        return trim(preg_replace("/[;,<>&=%:'“]/i", "", $field));
    }

    // Update user
    function update()
    {
        global $errors;
        global $user;

        try
        {
            $stmt = $user->update_by_id();
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

    // Update user fullname only
    function update_fullname_only()
    {
        global $errors;
        global $user;

        try
        {
            $stmt = $user->update_fullname_by_id();
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

        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : null;

        if(!isset($fullname))
            $errors['fullname'] = 'Fullname must not be null!';
        
        $password_old = isset($_POST['password_old']) ? prepare_field($_POST['password_old']) : null;
        $password_new = isset($_POST['password_new']) ? prepare_field($_POST['password_new']) : null;
        $password_new_confirm = isset($_POST['password_new_confirm']) ? prepare_field($_POST['password_new_confirm']) : null;

        if(!empty($password_new))
        {
            if(empty($password_old) || !isset($password_old))
                $errors['password_old'] = 'Old password is required!';
            else
                check_password($password_old);

            if (empty($password_new) || !isset($password_new))
                $errors['password_new'] = 'New password is required.';
            else if (strlen($password_new) < 8 || strlen($password_new) > 100)
                $errors['password_new'] = 'New password must be between 8 and 100 characters.';
            else if (!preg_match("/^[a-zA-Z0-9\s]+$/", $password_new))
                $errors['password'] = 'New password must be letters, numbers and spaces.';
            else if ($password_new != $password_new_confirm)
                $errors['password_new_confirm'] = 'Both new passwords must match!';
        }

        // Check errors
        if(empty($errors))
        {
            $user->id = $_SESSION['$user_id'];
            $user->fullname = $fullname;

            // Check if user wants to change password or not
            if(!empty($password_new))
            {
                $user->password = md5($password_new);
                update();
            }
            else
            {
                update_fullname_only();
            }
        }
    }
?>
