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

    echo($_SESSION['$user']);
    echo json_encode($data);
    exit();
}

//Check request
check_project_access($_POST['id']);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved project!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function for checking user acess to this project
function check_project_access($projectId){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML content As Json Data
    $data['content'] = '';

    //Get user id
    $userId = $_SESSION['$user_id'];

    //Query that check if user exists in database
    $query = "SELECT p.id AS id, p.name AS name, p.image As image, p.description AS description
              FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userId' AND up.role = 'CREATOR'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Hidden Project Id
            $data['content'] .= '<input type="hidden" id="projectHiddenId" name="projectHiddenId" value="'.$row['id'].'">';

            //Project name
            $data['name'] = $row['name'];

            //Description
            $data['description'] = $row['description'];

            //Image
            $data['image'] = $row['image'];
        }
        else{
            $errors['sql'] = 'Project not found or user has no given access to this project.';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>