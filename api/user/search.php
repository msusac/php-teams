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
    
    // Search users
    search();

    // Check if there are any errors
    if (!empty($errors)) 
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    } 
    else 
    {
        $data['success'] = true;
        $data['message'] = 'Search successful!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */

    // Preparing fields for database without SQL Injection
    function prepare_field($field)
    {
        return trim(preg_replace("/[;,<>&=%:'“]/i", "", $field));
    }

    // Prepare HTML table
    function prepare_table($stmt)
    {
        global $data;

        // Initializing HTML table
        $data['table'] = '';

        //Fetch rows
        while ($row = $stmt->fetch())
        {
            // Start table row
            $data['table'] .= '<tr onclick="readUserById(\'' . $row['id'] . '\')">';

            // Username
            $data['table'] .= '<td>' . $row['username'] . '</td>';

            // E-mail address
            $data['table'] .= '<td>' . $row['email'] . '</td>';

            // User Authority
            if ($row['user_authority'] == 'ROLE_ADMIN') {
                $data['table'] .= '<td>Admin</td>';
            } 
            else if ($row['user_authority'] == 'ROLE_USER') {
                $data['table'] .= '<td>User</td>';
            } 
            else {
                $data['table'] .= '<td>Not Activated</td>';
            }

            // Start end table row
            $data['table'] .= '</tr>';
        }
    }

    // Search users by param
    function search()
    {
        global $errors;
        global $user;

        // Prepare fields
        $user->username = isset($_POST['username']) ? prepare_field($_POST['username']) : null;
        $user->email = isset($_POST['email']) ? prepare_field($_POST['email']) : null;
        $user->authority = isset($_POST['authority']) ? prepare_field($_POST['authority']) : null;

        // Initialize where authority query
        $query_where_authority = '';

        // Initialize authority array
        $authority_array = array('ADMIN', 'USER', 'NOT_ACTIVATED');

        // Check if authority is admin, user or null
        if ($user->authority == $authority_array[0])
        {
            $query_where_authority = "AND a.name = 'ROLE_ADMIN' ";
        }
        else if ($user->authority == $authority_array[1])
        {
            $query_where_authority = "AND a.name = 'ROLE_USER' ";
        }
        else if ($user->authority == $authority_array[2])
        {
            $query_where_authority = "AND a.name IS null ";
        }

        // Get users by param
        try
        {
            $stmt = $user->select_by_param($query_where_authority);
            prepare_table($stmt);
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