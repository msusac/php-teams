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

//Request id
$requestId = $_POST['id'];

//Check request
check_request_access($requestId);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved request!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function for checking user access to selected request 
function check_request_access($requestId){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML content As Json Data
    $data['content'] = '';

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that checks if user has access to selected request
    $query = "SELECT r.id AS id, r.name AS name, r.description AS description, r.status AS status, 
              p.name AS project, p.id AS projectId, r.user_from_id AS userFromId, r.user_to_id AS userToId, 
              r.date_send AS sendOn, r.date_reply AS replyOn
              FROM request_table r
              INNER JOIN project_table p ON p.id = r.project_id
              WHERE r.id = '$requestId' AND (r.user_from_id = '$userId' OR r.user_to_id = '$userId')";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Hidden Request Id
            $data['content'] .= '<input type="hidden" id="requestHiddenId" name="requestHiddenId" value="'.$row['id'].'">';

            //Request
            $data['content'] .= '<div class="row"><div class="col s12 center-align"><h4>'.$row['name'].' '.'#'.$row['id'].'</h4></div></div>';

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

            //User From
            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>From</b></p></div>
                <div class="col s8 left-align"><p><i>'.get_username($row['userFromId']).'</i></p></div>
            </div>';

            //User To
            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>To</b></p></div>
                <div class="col s8 left-align"><p><i>'.get_username($row['userToId']).'</i></p></div>
            </div>';

            //Description
            $data['content'] .= '<div class="row card-panel">
                <b>Description</b>
                <p>'.nl2br($row['description']).'</p>
            </div>';

            //Sent On
            $date = strtotime($row['sendOn']);

            $data['content'] .= '<div class="row">
                <div class="col s4 right-align"><p><b>Sent On</b></p></div>
                <div class="col s8 left-align"><p><i>'.date("d/m/Y H:i", $date).'</i></p></div>
            </div>';

            //Updated By
            if(!empty($row['replyOn'])){
                $date = strtotime($row['replyOn']);

                $data['content'] .= '<div class="row">
                    <div class="col s4 right-align"><p><b>Replied On</b></p></div>
                    <div class="col s8 left-align"><p><i>'.$row['replyOn'].'</i></p></div>
                </div>';
            }

            //Add accept/reject button if user is recipient of request
            if($status == 'PENDING' && $userId == $row['userToId']){
                $data['content'] .= '<div class="row center-align">
                    <a class="modal-action waves-effect btn brand blue" onclick="acceptRequest()">Accept</a>
                    <a class="modal-action waves-effect btn brand red" onclick="rejectRequest()">Reject</a>
                </div>';
            }

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

//Function to get username by id
function get_username($userId){

    global $connection;

    //Query that check if user exists in database
    $query = "SELECT username FROM user_table
              WHERE id = '$userId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);
        return $row['username'];
    }
}

//Close connection
mysqli_close($connection);
?>