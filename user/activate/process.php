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
if(!isset($_SESSION['$user']) || empty($_SESSION['$user']) || $_SESSION['$userRole'] != 'ROLE_ADMIN'){
    $errors['session'] = 'Unauthorized access!';

    $data['success'] = false;
    $data['errors']  = $errors;

    echo json_encode($data);
    exit();
}

//Check user before activation
$username = prepare_field($_POST['username']);
check_user(prepare_field($username));

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'User sucessfully activated!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[;,<>&=%:'“ .]/i", "", $field));
}

//Function for activating user
function activate_user($id){

    global $connection;
    global $errors;

    //Query to insert user authority
    $query = "INSERT INTO user_authority_table (user_id, authority_id) VALUES ('$id', '2')";

    //Check result
    if (!$result = mysqli_query($connection, $query)) {
        $errors['sql'] = mysqli_error($connection);
    }
}

//Check if that user account is alread activated
function check_user($username){

    global $connection;
    global $errors;

    //Query that checks if user exists in database
    $query = "SELECT u.id AS userId, a.name AS role
              FROM user_table u 
              LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
              LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id 
              WHERE u.username = '$username'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Check if user has role before activation
            if(empty($row['role'])){
                activate_user($row['userId']);
            }
            else{
                $errors['activate'] = 'User account is already activated.';
            }
        }
        else{
            $errors['activate'] = 'User does not exists!';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>