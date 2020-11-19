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
$name = prepare_field($_POST['name']);
$description = prepare_field($_POST['description']);
$projectId = prepare_field($_POST['project']);
$userToId = prepare_field($_POST['user']);

//Validate form
validate_form($name, $description, $projectId, $userToId);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Request successfully sent!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[<>&=%:'“]/i", "", $field));
}

//Function to check if user has access to this project
function check_project_access($projectId){

    global $connection;
    global $errors;

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that check if user has access to this project
    $query = "SELECT p.name AS project FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userId' 
              AND up.role = 'CREATOR'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(empty($row))
            $errors['project'] = 'Please select project that belongs to you!';
        
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to check if user is member of project
function check_project_users($projectId, $userToId){

    global $connection;
    global $errors;

    //Query that check if user is member of existing project
    $query = "SELECT * FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userToId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row))
            $errors['user'] = 'User is already member of this selected project!';
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Check if request sent to user already exists
function check_request($projectId, $userToId){

    global $connection;
    global $errors;

    //Get user id
    $userFromId = $_SESSION['$userId'];

    //Query that check if request already exists
    $query = "SELECT * FROM request_table
              WHERE user_from_id = '$userFromId' 
              AND user_to_id = '$userToId'
              AND project_id = '$projectId'
              AND status = 'PENDING'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row))
            $errors['project'] = 'You have already sent same request to that user before!';
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}
//Function to check if user exists
function check_user($userToId){
    
    global $connection;
    global $errors;

    //Query that check if user exists in database
    $query = "SELECT * FROM user_table
              WHERE id = '$userToId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(empty($row))
            $errors['user'] = 'Please select correct user!';
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to save request
function save_request($name, $description, $projectId, $userToId){

    global $connection;
    global $errors;
    
    //Get user id
    $userFromId = $_SESSION['$userId'];

    //Query to save request
    $query = "INSERT INTO request_table 
             (name, description, status, user_from_id, user_to_id, project_id)
             VALUES ('$name', '$description', 'PENDING', '$userFromId', '$userToId', '$projectId')";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to validate form
function validate_form($name, $description, $projectId, $userToId){

    global $errors;

    if (empty($name))
        $errors['name'] = "Project name is required.";
    else if (strlen($name) < 5 || strlen($name) > 100)
        $errors['name'] = "Project name must be between 5 and 100 characters.";

    if (empty($description))
        $errors['description'] = "Project description is required.";
    else if (strlen($description) < 5 || strlen($description) > 550)
        $errors['description'] = "Description must be between 5 and 550 characters";

    check_user($userToId);
    check_request($projectId, $userToId);

    check_project_access($projectId);
    check_project_users($projectId, $userToId);

    if(empty($errors)){
        save_request($name, $description, $projectId, $userToId);
    }
}

//Close connection
mysqli_close($connection);
?>