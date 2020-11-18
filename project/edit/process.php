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

$name = prepare_field($_POST['name']);
$description = prepare_field($_POST['description']);
$image = $_FILES['image']['name'];
$projectId = $_POST['projectHiddenId'];

//Check request
check_project_access($projectId, $name, $description, $image);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully updated project!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[<>&=%:'“]/i", "", $field));
}

//Function for checking user acess to this project
function check_project_access($projectId, $name, $description, $image){

    global $connection;
    global $errors;

    //Get user id
    $userId = $_SESSION['$user_id'];

    //Query that check if user exists in database
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
            validate_form($projectId, $name, $description, $image);
        }
        else{
            $errors['sql'] = 'Project not found or user has no given access to this project.';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Update project without image
function update_project($projectId, $name, $description){

    global $connection;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Query for updating project
    $query = "UPDATE project_table 
              SET name = '$name', description = '$description', updated_by = '$user'
              WHERE id = '$projectId'";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
    else{
        update_user_project($projectId, $name);
    }
}

//Update project with image
function update_project_image($projectId, $name, $description, $image){

    global $connection;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Image file directory
    define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');

    $target = APP_ROOT . "resources/img/uploads/" . basename($image);

    //Upload image
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target) && !empty($image)) {
        $errors['image'] = "Failed to upload image";
    }
    else{
        //Query for updating project
        $query = "UPDATE project_table 
                SET name = '$name', description = '$description', updated_by = '$user', image = '$image'
                WHERE id = '$projectId'";

        //Check if there is any errors
        if(!$result = mysqli_query($connection, $query)){
            $errors['sql'] = mysqli_error($connection);
        }
        else{
            update_user_project($projectId, $name);
        }
    }
}

//Function for updating user_project table
function update_user_project($projectId, $name){
    
    global $connection;
    global $errors;

    //Query for updating project
    $query = "UPDATE user_project_table 
        SET project = '$name'
        WHERE project_id = '$projectId'";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Validate form
function validate_form($projectId, $name, $description, $image)
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

    //Check errors
    if(empty($errors)){
        //Check if user wants to change image or not
        if(isset($_POST['save_image'])){
            update_project_image($projectId, $name, $description, $image);
        }
        else{
            update_project($projectId, $name, $description);
        }
    }
}

//Close connection
mysqli_close($connection);
?>