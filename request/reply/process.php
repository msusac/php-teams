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
if(!isset($_SESSION['$user'])){
    $errors['session'] = 'Unauthorized access!';

    $data['success'] = false;
    $data['errors']  = $errors;

    echo json_encode($data);
    exit();
}

//Prepare fields
$requestId = prepare_field($_POST['id']);
$status = prepare_field($_POST['status']);

//Validate form
validate_form($requestId, $status);

$data = array();

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;

    if($status == 'ACCEPTED'){
        $data['message'] = 'You have accepted request!';
    }
    else{
        $data['message'] = 'You have rejected request!';
    }
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[<>&=%:'“]/i", "", $field));
}

//Function to add user as helper to selected project
function add_helper_to_project(){

    global $connection;
    global $data;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Get user id
    $userId = $_SESSION['$userId'];

    //Project
    $project = $data['project'];

    //Project id
    $projectId = $data['projectId'];

    //Query to insert user to project group
    $query = "INSERT INTO user_project_table 
              VALUES ('$userId', '$projectId', '$user', '$project', 'HELPER')";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function for checking user access to pending request 
function check_request_access($requestId){

    global $connection;
    global $data;
    global $errors;

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that checks if user has access to pending request
    $query = "SELECT p.id AS projectId, p.name AS project FROM request_table r
              INNER JOIN project_table p ON p.id = r.project_id
              WHERE r.id = '$requestId' AND status = 'PENDING' AND r.user_to_id = '$userId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){
            $data['projectId'] = $row['projectId'];
            $data['project'] = $row['project'];
        }
        else{
            $errors['sql'] = 'Pending request does not exists!';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to update request
function update_request($requestId, $status){

    global $connection;
    global $errors;

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query to update request
    $query = "UPDATE request_table SET status = '$status' WHERE id = '$requestId' AND user_to_id = '$userId'";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
    //Check if user accepted request or not
    else if($status == 'ACCEPTED'){
        add_helper_to_project();
    }
}

//Validate form
function validate_form($requestId, $status){

    global $errors;

    if(!in_array($status, Array('ACCEPTED', 'REJECTED')))
        $errors['sql'] = 'Invalid reply type!';

    check_request_access($requestId);

    if(empty($errors)){
        update_request($requestId, $status);
    }
}