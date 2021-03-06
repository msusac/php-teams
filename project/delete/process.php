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

//Project id
$projectId = $_POST['id'];

//Check request
check_project_access($projectId);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully deleted project!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function for checking user access to selected project
function check_project_access($projectId){

    global $connection;
    global $errors;

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that checks if user is creator of selected project
    $query = "SELECT * FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userId' AND up.role = 'CREATOR'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){
            delete_project($projectId);
        }
        else{
            $errors['sql'] = 'Project not found or user has no given access to this project.';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Delete project
function delete_project($projectId){

    global $connection;
    global $errors;

    //Query that check if user exists in database before deleting
    $query = "DELETE FROM project_table
              WHERE id = '$projectId'";

    //Execute query
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>