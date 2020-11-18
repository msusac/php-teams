<?php
//Start session
ob_start();
session_start();

//Check if access is from AJAX/JS Scripts
include('../../config/ajax_connect.php');

//Check connection with database
include('../../config/db_connect.php');

//Check user access
if(!isset($_SESSION['$user']) || empty($_SESSION['$user'])){
    $errors['session'] = 'Unauthorized access!';

    $data['success'] = false;
    $data['errors']  = $errors;

    echo($_SESSION['$user']);
    echo json_encode($data);
    exit();
}

//Initialize data and error arrays
$errors = array();
$data = array();

//Task id
$taskId = $_POST['id'];

//Check request
check_project_access($taskId);

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
function check_project_access($taskId){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML content As Json Data
    $data['content'] = '';

    //Get user id
    $userId = $_SESSION['$user_id'];

    //Query that check if user exists in database
    $query = "SELECT t.id AS id, t.name AS name, 
              t.description AS description, t.status AS status,
              t.created_by AS createdBy, t.updated_by AS updatedBy,
              t.date_created AS createdOn, t.date_updated AS updatedOn,
              t.date_start AS dateStart, t.date_end AS dateEnd,
              p.name AS project, p.id AS projectId
              FROM task_table t
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

            //Hidden Project Id
            $data['content'] .= '<input type="hidden" id="taskHiddenId" name="taskHiddenId" value="'.$row['id'].'">';

            //Task Name
            $data['content'] .= '<div class="row"><div class="col s12 center-align"><h4>'.$row['name'].' '.'#'.$row['id'].'</h4></div></div>';

            //Description
            $data['content'] .= '<div class="row card-panel">
                <b>Description</b>
                <p>'.nl2br($row['description']).'</p>
            </div>';

            //Project
            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>Project</b></p></div>
                <div class="col s8 left-align"><p><i>'. $row['project'] . ' - '. $row['projectId'] .'</i></p></div>
            </div>';

            //Status
            $status = preg_replace("/_/i", " ", $row['status']);

            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>Status</b></p></div>
                <div class="col s8 left-align"><p><i>'. $status . '</i></p></div>
            </div>';

            //Starting date
            if(!empty($row['dateStart'])){
                $date = strtotime($row['dateStart']);

                $data['content'] .= '<div class="row">
                    <div class="col s4 right-align"><p><b>Starting date</b></p></div>
                    <div class="col s8 left-align"><p><i>'.date("d/m/Y H:i", $date).'</i></p></div>
                </div>';
            }

            //Ending date
            if(!empty($row['dateEnd'])){
                $date = strtotime($row['dateEnd']);

                $data['content'] .= '<div class="row">
                    <div class="col s4 right-align"><p><b>Ending date</b></p></div>
                    <div class="col s8 left-align"><p><i>'.date("d/m/Y H:i", $date).'</i></p></div>
                </div>';
            }

            //Created By
            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>Created By</b></p></div>
                <div class="col s8 left-align"><p><i>'.$row['createdBy'].'</i></p></div>
            </div>';

            //Created On
            $date = strtotime($row['createdOn']);

            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>Created On</b></p></div>
                <div class="col s8 left-align"><p><i>'.date("d/m/Y H:i", $date).'</i></p></div>
            </div>';

            //Updated By
            if(!empty($row['updatedBy'])){
                $data['content'] .= '<div class="row">
                    <div class="col s4 right-align"><p><b>Updated By</b></p></div>
                    <div class="col s8 left-align"><p><i>'.$row['updatedBy'].'</i></p></div>
                </div>';
            }

            //Updated On
            if(!empty($row['updatedOn'])){
                $date = strtotime($row['updatedOn']);

                $data['content'] .= '<div class="row">
                    <div class="col s4 right-align"><p><b>Updated By</b></p></div>
                    <div class="col s8 left-align"><p><i>'.date("d/m/Y H:i", $date).'</i></p></div>
                </div>';
            }

            //Add buttons
            $data['content'] .= '<div class="row center-align">
                <a class="modal-action waves-effect btn brand blue" onclick="editTask()">Edit</a>
                <a class="modal-action waves-effect btn brand red" onclick="deleteTask()">Delete</a>
            </div>';
            
            //Add close button
            $data['content'] .= '<div class="row center-align"><a class="modal-action modal-close waves-effect btn brand">Close</a></div>';
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