<?php
//Start session
ob_start();
session_start();

//Check user access
if(!isset($_SESSION['$user']) || empty($_SESSION['$user'])){
    $errors['session'] = 'Unauthorized access!';

    $data['success'] = false;
    $data['errors']  = $errors;

    echo json_encode($data);
    exit();
}

//Check if access is from AJAX/JS Scripts
include('../../config/ajax_connect.php');

//Check connection with database
include('../../config/db_connect.php');

//Initialize data and error arrays
$errors = array();
$data = array();

//Preparing fields
$fullname = prepare_field_fullname($_POST['fullname']);
$password_old = prepare_field($_POST['password_old']);
$password_new = prepare_field($_POST['password_new']);
$password_new_repeat = prepare_field($_POST['password_new_repeat']);

//Check if user wants to change password or not
if(!empty($password_new)){
    //Validate form
    validate_form($fullname, $password_old, $password_new, $password_new_repeat);
}
else{
    //Update only fullname
    update_user_only_fullname($fullname);
}

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
}
else {
    $data['success'] = true;
    $data['user'] = $_SESSION['$user'];
    $data['message'] = 'User Details sucessfully changed!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[;,<>&=%:'“ .]/i", "", $field));
}

//Preparing fields for database without SQL Injection
function prepare_field_fullname($field)
{
    return trim(preg_replace("/[;,<>&=%:'“]/i", "", $field));
}

//Check if password is correct
function check_password($password_old)
{
    global $connection;
    global $errors;

    //Get username from session;
    $username = $_SESSION['$user'];

    //Encrypt password
    $password = md5($password_old);

    //Query that checks if user already exists in database
    $query = "SELECT * FROM user_table WHERE username = '$username' AND password = '$password'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(empty($row))
            $errors['password_old'] = 'Wrong old password.';
        
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Update Only User Fullname
function update_user_only_fullname($fullname)
{
    global $connection;

    $username = $_SESSION['$user'];

    $query = "UPDATE user_table SET fullname = '$fullname' WHERE username = '$username'";

    if (!$result = mysqli_query($connection, $query)) {
        $errors['sql'] = mysqli_error($connection);
    }
}

//Update Username
function update_user($fullname, $password_new)
{
    global $connection;

    $username = $_SESSION['$user'];

    $query = "UPDATE user_table SET fullname = '$fullname', password = '$password_new' WHERE username = '$username'";

    if (!$result = mysqli_query($connection, $query)) {
        $errors['sql'] = mysqli_error($connection);
    }
}

//Validate form
function validate_form($fullname, $password_old, $password_new, $password_new_repeat)
{
    global $errors;

    if(empty($password_old))
        $errors['password_old'] = 'Old password is required!';
    else 
        check_password($password_old);
       
    if (empty($password_new))
        $errors['password_new'] = 'New password is required.';
    else if (strlen($password_new) < 8 || strlen($password_new) > 100)
        $errors['password_new'] = 'New password must be between 8 and 100 characters.';
    else if (!preg_match("/^[a-zA-Z0-9\s]+$/", $password_new))
        $errors['password'] = 'New password must be letters, numbers and spaces.';
    else if ($password_new != $password_new_repeat)
        $errors['password_new'] = 'Both new passwords must match!';

    //Check errors
    if(empty($errors)){
        //Update user
        update_user($fullname, md5($password_new));
    }
}

//Close connection
mysqli_close($connection);
?>