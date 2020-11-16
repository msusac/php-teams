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
$image = $_FILES['image']['name'];

//Validate from
validate_form($name, $description, $image);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Project sucessfully saved!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[<>&=%:'“]/i", "", $field));
}

//Add user as creator to latest project he created
function add_creator_to_project(){

    global $connection;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Get user id
    $userId = $_SESSION['$user_id'];

    //Query that search user's latest project
    $query = "SELECT id, name FROM project_table 
              WHERE created_by = '$user' 
              ORDER BY date_created DESC
              LIMIT 0,1";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        if(!empty($row)){
            $projectId = $row['id'];
            $project = $row['name'];

            //Query to insert user to project group
            $query = "INSERT INTO user_project_table 
                      VALUES ('$userId', '$projectId', '$user', '$project', 'CREATOR')";

            //Check if there is any errors
            if(!$result = mysqli_query($connection, $query)){
                $errors['sql'] = mysqli_error($connection);
            }
        }
        else{
            $errors['sql'] = mysqli_error($connection);
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Save project without image
function save_project($name, $description)
{
    global $connection;
    global $errors;
    
    //Get user
    $user = $_SESSION['$user'];

    //Query for inserting project
    $query = "INSERT INTO project_table (name, description, created_by) VALUES ('$name', '$description', '$user')";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
    else{
        add_creator_to_project();
    }
}

//Save project with image
function save_project_image($name, $description, $image)
{
    global $connection;
    global $errors;
    
    //Get user
    $user = $_SESSION['$user'];

    //Image file directory
    define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');

    $target = APP_ROOT . "resources/img/uploads/" . basename($image);

    //Upload image
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $errors['image'] = "Failed to upload image";
    }
    else{
        //Query for inserting project
        $query = "INSERT INTO project_table (name, description, created_by, image) VALUES ('$name', '$description', '$user', '$image')";

        //Check if there is any errors
        if(!$result = mysqli_query($connection, $query)){
            $errors['sql'] = mysqli_error($connection);
        }
        else{
            add_creator_to_project();
        }
    }
}

//Validate form
function validate_form($name, $description, $image)
{
    global $errors;

    if (empty($name))
        $errors['name'] = "Project name is required.";
    else if (strlen($name) < 5 || strlen($name) > 100)
        $errors['name'] = "Project name must be between 5 and 100 characters.";

    if (empty($description))
        $errors['description'] = "Project description is required.";
    else if (strlen($description) < 5 || strlen($description) > 550)
        $errors['description'] = "Description must be between 5 and 550 characters.";

    //Check errors
    if(empty($errors)){
        //Check if user wants to save image or not
        if(!empty($image)){
            save_project_image($name, $description, $image);
        }
        else{
            save_project($name, $description);
        }
    }
}

//Close connection
mysqli_close($connection);
?>