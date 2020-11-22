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

//Project task id
$taskId = $_POST['id'];

//Function to check if user has access to selected project task
check_project_task_access($taskId);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved project task!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function to check if user has access to selected project task
function check_project_task_access($taskId){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML content As Json Data
    $data['content'] = '';

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query to get project task
    $query = "SELECT t.id AS id, t.name AS name, p.name AS project, p.id AS projectId,
              t.status AS status, t.description AS description,
              t.date_start AS dateStart, t.date_end AS dateEnd
              FROM task_table t
              INNER JOIN project_table p ON p.id = t.project_id
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE up.user_id = '$userId' AND t.id = '$taskId'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Hidden Project Id
            $data['content'] .= '<input type="hidden" id="projectHiddenId" name="projectHiddenId" value="'.$row['projectId'].'">';

            //Hidden Project Task Id
            $data['content'] .= '<input type="hidden" id="taskHiddenId" name="taskHiddenId" value="'.$row['id'].'">';

            //Project task name
            $data['name'] = $row['name'];

            //Project
            $data['project'] = $row['project'] . ' - ' . $row['projectId'];

            //Project task status
            $data['status'] = $row['status'];

            //Description
            $data['description'] = $row['description'];

            //Starting date
            if(!empty($row['dateStart'])){
                $data['dateStart'] = date("d/m/Y", strtotime($row['dateStart']));
                $data['timeStart'] = date("H:i", strtotime($row['dateStart']));
            }

            //Ending date
            if(!empty($row['dateEnd'])){
                $data['dateEnd'] = date("d/m/Y", strtotime($row['dateEnd']));
                $data['timeEnd'] = date("H:i", strtotime($row['dateEnd']));
            }
        }
        else{
            $errors['sql'] = "Project task not found or user has no access to it!";
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>