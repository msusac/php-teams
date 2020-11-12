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
if(!isset($_SESSION['$user']) || empty($_SESSION['$user'])){
    $errors['session'] = 'Unauthorized access!';

    $data['success'] = false;
    $data['errors']  = $errors;

    echo json_encode($data);
    exit();
}

//Get user
get_user_fullname_by_username($_SESSION['$user']);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved user data!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Get User Fullname by Username
function get_user_fullname_by_username($username)
{
    global $connection;
    global $data;

    //Query to fetch user's fullname by username
    $query = "SELECT fullname FROM user_table
              WHERE username = '$username'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        //Fetch row
        $row = mysqli_fetch_assoc($result);
        $data['fullname'] = $row['fullname'];
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>