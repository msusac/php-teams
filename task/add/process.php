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

validate_form($name, $description, $projectId);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Project Task sucessfully saved!';
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
    $userId = $_SESSION['$user_id'];

    //Query that check if user exists in database
    $query = "SELECT * FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(empty($row))
            $errors['project'] = 'Please select project that is associated with you!';
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function save project task
function save_project_task($name, $description, $projectId){

    global $connection;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Query for inserting project task
    $query = "INSERT INTO task_table (name, description, status, created_by, project_id)
              VALUES ('$name', '$description', 'NOT_STARTED', '$user', '$projectId')";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
    else{
        update_project($projectId);
    }
}

//Function to update project
function update_project($projectId){

    global $connection;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Query for updating project
    $query = "UPDATE project_table SET updated_by = '$user' WHERE id = $projectId";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Validate form
function validate_form($name, $description, $projectId)
{
    global $errors;

    if (empty($name))
        $errors['name'] = "Project name is required.";
    else if (strlen($name) < 5 || strlen($name) > 100)
        $errors['name'] = "Project name must be between 5 and 100 characters.";

    if (empty($description))
        $errors['description'] = "Project description is required.";
    else if (strlen($description) < 5 || strlen($description) > 550)
        $errors['description'] = "Description must be between 5 and 550 characters";

    check_project_access($projectId);

    if(empty($errors)){
        save_project_task($name, $description, $projectId);
    }
}

//Close connection
mysqli_close($connection);
?>