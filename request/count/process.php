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

//Function to count all pending requests that user received
count_pending_request();

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved request count!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function to count all pending requests that user received
function count_pending_request(){

    global $connection;
    global $data;

    //Get user id
    $userToId = $_SESSION['$userId'];

    //Query to count all pending requests that user received
    $query = "SELECT COUNT(id) AS count
              FROM request_table
              WHERE user_to_id = '$userToId' AND status = 'PENDING'";

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