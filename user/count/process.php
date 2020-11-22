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

//Function to count all user accounts that are not activated
count_not_activated_users();

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved user count!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function to count all user accounts that are not activated
function count_not_activated_users(){

    global $connection;
    global $data;

    //Query to count all user accounts that are not activated
    $query = "SELECT COUNT(u.id) AS count
              FROM user_table u 
              LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
              LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id
              WHERE a.name IS NULL";

    //Check if there are any errors
    if($result = mysqli_query($connection, $query)){
        $row = mysqli_fetch_assoc($result);

        if($row['count'] > 9){
            $data['count'] = '9+';
        }
        else if($row['count'] > 0){
            $data['count'] = $row['count'];
        }
    }
}

//Close connection
mysqli_close($connection);
?>