<?php
//Session start
ob_start();
session_start();

//Check if access is from AJAX/JS Scripts
include('../../config/ajax_connect.php');

//Check connection with database
include('../../config/db_connect.php');

//Initialize data and error array
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
$status = prepare_field($_POST['status']);
$taskId = prepare_field($_POST['taskHiddenId']);
$projectId = prepare_field($_POST['projectHiddenId']);
$dateStart = prepare_field_datetime($_POST['date-start']);
$dateEnd = prepare_field_datetime($_POST['date-end']);
$timeStart = prepare_field_datetime($_POST['time-start']);
$timeEnd = prepare_field_datetime($_POST['time-end']);

//Validate form
validate_form($name, $description, $status, $taskId, $projectId,
$dateStart, $dateEnd, $timeStart, $timeEnd);

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

//Preparing datetime fields for database without SQL Injection
function prepare_field_datetime($field){
    return trim(preg_replace("/[<>&=%'“]/i", "", $field));
}

//Function to check user access to selected project
function check_project_access($projectId){

    global $connection;
    global $errors;
    global $data;

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that check if user has access to selected project
    $query = "SELECT p.id AS id, p.name AS name FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(empty($row)){
            $errors['project'] = 'Please select project that is associated with you!';
        }
        else{
            $data['project'] = $row['name'] . ' - ' . $row['id'];
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to check if user has access to this project task
function check_project_task_access($projectId, $taskId){

    global $connection;
    global $errors;

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that checks if user has access to this project task
    $query = "SELECT * FROM task_table t
              INNER JOIN project_table p ON p.id = t.project_id
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE up.user_id = '$userId' AND t.id = '$taskId'
              AND p.id = '$projectId'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(empty($row))
            $errors['sql'] = 'Project task not found or user has no access to it!';
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to update project
function update_project($projectId){

    global $connection;
    global $errors;

    //Get user
    $user = $_SESSION['$user'];

    //Query for updating project
    $query = "UPDATE project_table SET updated_by = '$user' WHERE id = '$projectId'";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
}

//Function to update project task without starting/ending dates
function update_project_task($name, $description, $status, $taskId, $projectId){

    global $connection;
    global $errors;
    global $data;

    //Get user
    $user = $_SESSION['$user'];

    //Query for updating project task
    $query = "UPDATE task_table 
              SET name = '$name', description = '$description', status = '$status', 
              updated_by = '$user', date_start = null, date_end = null
              WHERE id = '$taskId'";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
    else{
        $data['status'] = preg_replace("/_/i", " ", $status);
        update_project($projectId);
    }
}

//Function to update project task with starting/ending dates
function update_project_task_timestamp($name, $description, $status, $taskId, $projectId, $timestampStart, $timestampEnd){

    global $connection;
    global $errors;
    global $data;

    //Get user
    $user = $_SESSION['$user'];

    //Query for updating project task
    $query = "UPDATE task_table 
              SET name = '$name', description = '$description', status = '$status', 
              updated_by = '$user', date_start = '$timestampStart', date_end = '$timestampEnd'
              WHERE id = '$taskId'";

    //Check if there is any errors
    if(!$result = mysqli_query($connection, $query)){
        $errors['sql'] = mysqli_error($connection);
    }
    else{
        $data['status'] = preg_replace("/_/i", " ", $status);
        update_project($projectId);
    }
}

//Validate form
function validate_form($name, $description, $status, $taskId, $projectId,
$dateStart, $dateEnd, $timeStart, $timeEnd)
{
    global $errors;

    //Set timestamps
    $timestampStart = null;
    $timestampEnd = null;

    //Check if user wants to set starting and ending date
    if(!empty($dateStart) || !empty($dateEnd) || !empty($timeStart) || !empty($timeEnd)){

        if(empty($dateStart))
            $errors['dateStart'] = 'Starting date must not be empty.';
        else if(!date('d/m/Y', strtotime(str_replace('/', '-', $dateStart))))
            $errors['dateStart'] = 'Starting date format must be dd/mm/YYYY';
        
        if(empty($dateEnd))
            $errors['dateEnd'] = 'Ending date must not be empty.';
        else if(!date('d/m/Y', strtotime(str_replace('/', '-', $dateEnd))))
            $errors['dateEnd'] = 'Ending date format must be dd/mm/YYYY';

        if(empty($timeStart))
            $errors['timeStart'] = 'Starting time must not be empty.';
        else if(!date('H:i', strtotime($timeStart)))
            $errors['timeStart'] = 'Starting time format must be hh:mm';

        if(empty($timeEnd))
            $errors['timeEnd'] = 'Ending time must not be empty.';
        else if(!date('H:i', strtotime($timeEnd)))
            $errors['timeEnd'] = 'Ending time format must be hh:mm';

        if(empty($errors)){
            $timestampStart = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $dateStart) . ' ' . $timeStart));
            $timestampEnd = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $dateEnd) . ' ' . $timeEnd));

            if($timestampStart > $timestampEnd){
                $errors['dateStart'] = 'Starting datetime must not be greater than ending datetime.';
            }
        }
    }

    if (empty($name))
        $errors['name'] = "Project name is required.";
    else if (strlen($name) < 5 || strlen($name) > 100)
        $errors['name'] = "Project name must be between 5 and 100 characters.";

    if (empty($description))
        $errors['description'] = "Project description is required.";
    else if (strlen($description) < 5 || strlen($description) > 550)
        $errors['description'] = "Description must be between 5 and 550 characters";

    if(empty($status) || !in_array($status, array('NOT_STARTED', 'IN_PROGRESS', 'DONE', 'REVERSED')))
        $errors['status'] = "Please select status!";

    check_project_access($projectId);

    check_project_task_access($projectId, $taskId);

    if(empty($errors)){
        //Check if user wants to save starting/ending dates
        if(!empty($timestampStart) && !empty($timestampEnd))
             update_project_task_timestamp($name, $description, $status, $taskId, $projectId, $timestampStart, $timestampEnd);
        else
            update_project_task($name, $description, $status, $taskId, $projectId);
    }
}

//Close connection
mysqli_close($connection);
?>