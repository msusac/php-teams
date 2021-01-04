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

    // Check request
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
        read();
    else
        $errors['general'] = "Invalid request!";

    // Check if there are any errors
    if (!empty($errors)) 
    {
        $data['success'] = false;
        $data['errors']  = $errors;
    } 
    else 
    {
        $data['success'] = true;
        $data['message'] = 'Successfully retrieved user!';
    }

    // Return all data to an AJAX call
    echo json_encode($data);

    /*                              */
    /*          Functions           */
    /*                              */

    // Prepare HTML table
    function prepare_table($row)
    {
        global $data;

        // Initializing HTML table
        $data['table'] = '';

        // Username
        $data['table'] .=
        '<tr>
            <td><b>Username</b></td>
            <td><i>' . $row['username'] . '</i></td>
        </tr>';

        // Fullname
        if (!empty($row['fullname'])) 
        {
            $data['table'] .=
                '<tr>
                    <td><b>Fullname</b></td>
                    <td><i>' . $row['fullname'] . '</i></td>
                </tr>';
        } 
        else 
        {
            $data['table'] .=
                '<tr>
                    <td><b>Fullname</b></td>
                    <td><i>None</i></td>
                </tr>';
        }

        // Email address
        $data['table'] .=
            '<tr>
                <td><b>E-mail address</b></td>
                <td><i>' . $row['email'] . '</i></td>
            </tr>';

        // Joined at
        $data['table'] .=
            '<tr>
                <td><b>Joined at</b></td>
                <td><i>' . $row['created_at'] . '</i></td>
            </tr>';

        // User Authority
        if ($row['user_authority'] == 'ROLE_ADMIN') 
        {
            $data['table'] .=
                '<tr>
                    <td><b>Role/b></td>
                    <td><i>Admin</i></td>
                </tr>';
        } 
        else if ($row['user_authority'] == 'ROLE_USER') 
        {
            $data['table'] .=
                '<tr>
                    <td><b>Role</b></td>
                    <td><i>User</i></td>
                </tr>';
        } 
        else 
        {
            $data['table'] .=
                '<tr>
                    <td><b>Role</b></td>
                    <td><i>Not Activated</i></td>
                </tr>';
        }

        // User actions
        $data['table'] .= '<tr><td><b>Actions</b></td><td>';

        // Check if fetched profile belongs to currently signed user
        if ($_SESSION['$user'] == $row['username'])
            $data['table'] .='<a class="modal-action waves-effect btn brand" href="#" onclick="openUserEdit()">Edit</a>';

        // Add button for activating user for admin users only
        if ($_SESSION['$user_authority'] == 'ROLE_ADMIN' && empty($row['user_authority']))
            $data['table'] .='<a class="modal-action waves-effect btn brand" href="#" onclick="activateUserById(\'' . $row['id'] . '\')">Activate</a>';
        
        
        // End HTML table
        $data['table'] .= '</td></tr>';
    }

    // Read User by ID
    function read()
    {
        global $errors;
        global $user;

        // Set params
        $user->id = $_GET['id'];

        // Get user by ID
        try
        {
            $stmt = $user->select_by_id();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if fetched row is empty or not
            if (!empty($row))
            {
                prepare_table($row);
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