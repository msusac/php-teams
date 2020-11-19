<?php
//Start session
ob_start();
session_start();

//Check if access is from AJAX/JS Scripts
include('../../config/ajax_connect.php');

//Check connection with database
include('../../config/db_connect.php');

//Initialize data and error arrays
$errors = array();
$data = array();

//Check user access
if(isset($_SESSION['$user']) || !empty($_SESSION['$user'])){
    $errors['session'] = 'Unauthorized access!';

    $data['success'] = false;
    $data['errors']  = $errors;

    echo json_encode($data);
    exit();
}

//Prepare fields
$username = prepare_field($_POST['username']);
$password = prepare_field($_POST['password']);

//Validate form
validate_form($username, $password);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Login sucessfull! Please wait!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[;,<>&=%:'“ .]/i", "", $field));
}

//Function for login operation
function login_user($username, $password)
{
    global $connection;
    global $errors;

    //Query that checks if user exists in database
    $query = "SELECT u.id AS userId, u.username AS username , a.name AS role
              FROM user_table u 
              LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
              LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id 
              WHERE u.username = '$username' AND u.password = '$password'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Check if user has role
            if(!empty($row['role'])){

                $_SESSION['$user'] = $row['username'];
                $_SESSION['$userId'] = $row['userId'];
                $_SESSION['$userRole'] = $row['role'];
            }
            else{
                $errors['login'] = 'Your account is not activated.';
            }
        }
        else{
            $errors['login'] = 'Wrong username and/or password.';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Validate form
function validate_form($username, $password)
{
    global $errors;

    if (empty($username))
        $errors['username'] = 'Username is required.';

    if (empty($password))
        $errors['password'] = 'Password is required.';

    //Check errors
    if(empty($errors)){
        //Login user
        login_user($username, md5($password));
    }
}

//Close connection
mysqli_close($connection);
?>