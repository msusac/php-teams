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

//Project Task id
$taskId = $_POST['id'];

//Check request
check_task_access($taskId);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully deleted project task!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function for checking user acess to this project task
function check_task_access($taskId){

    global $connection;
    global $errors;

    //Get user id
    $userId = $_SESSION['$user_id'];

    //Query that check if user exists in database
    $query = "SELECT * FROM task_table t
              INNER JOIN project_table p ON p.id = t.project_id
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE t.id = '$taskId' AND up.user_id = '$userId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){
            delete_task($taskId);
        }
        else{
            $errors['sql'] = 'Project task not found or user has no given access to this project task';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Delete project task
function delete_task($taskId){

    global $connection;
    global $errors;

    //Query that check if user exists in database
    $query = "DELETE FROM task_table
              WHERE id = '$taskId'";

    //Execute query
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>