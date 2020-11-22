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
$email = prepare_field_email($_POST['email']);
$password = prepare_field($_POST['password']);
$password_repeat = prepare_field($_POST['password_repeat']);

//Validate form
validate_form($username, $email, $password, $password_repeat);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} 
else {
    $data['success'] = true;
    $data['message'] = 'Registration successful! Your account will be activated soon!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[;,<>&=%:'“ .]/i", "", $field));
}

//Preparing e-mail field for database without SQL Injectio
function prepare_field_email($field)
{
    return trim(preg_replace("/[;,<>&=%:'“ ]/i", "", $field));
}

//Check if email address already exists in database
function check_email($email)
{
    global $connection;
    global $errors;

    //Query that checks if email address already exists in database
    $query = "SELECT * FROM user_table WHERE email = '$email'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){
            $errors['email'] = 'E-mail address already taken!';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Check if username already exists in database
function check_username($username)
{
    global $connection;
    global $errors;

    //Query that checks if username already exists in database
    $query = "SELECT * FROM user_table WHERE username = '$username'";

    //Fetch results
    $result = mysqli_query($connection, $query);

    //Check row
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){
            $errors['username'] = 'Username already taken!';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Save user
function save_user($username, $email, $password)
{
    global $connection;
    global $errors;

    //Query for inserting user
    $query = "INSERT INTO user_table (username, email, password) VALUES ('$username', '$email', '$password')";

    $result = mysqli_query($connection, $query);

    //Check if there is any errors
    if(!$result){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Validate form
function validate_form($username, $email, $password, $password_repeat)
{
    global $errors;

    if (empty($username))
        $errors['username'] = 'Username is required.';
    else if (strlen($username) < 5 || strlen($username) > 55)
        $errors['username'] = 'Username must be between 5 and 55 characters.';
    else if (!preg_match("/^[a-zA-Z0-9]+$/", $username))
        $errors['username'] = 'Username must be letters and numbers. No whitespaces allowed!';
    else 
        check_username($username);
    
    if (empty($email))
        $errors['email'] = 'E-mail address is required.';
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'E-mail address must be valid.';
    else
        check_email($email);
        

    if (empty($password))
        $errors['password'] = 'Password is required.';
    else if (strlen($password) < 8 || strlen($password) > 100)
        $errors['password'] = 'Password must be between 8 and 100 characters.';
    else if (!preg_match("/^[a-zA-Z0-9\s]+$/", $password))
        $errors['password'] = 'Password must be letters, numbers and spaces.';
    else if ($password != $password_repeat)
        $errors['password'] = 'Both passwords must match!';

    //Check errors
    if(empty($errors)){
        //Save user
        save_user($username, $email, md5($password));
    }
}

//Close connection
mysqli_close($connection);
?>