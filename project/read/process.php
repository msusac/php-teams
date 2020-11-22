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

    echo json_encode($data);
    exit();
}

//Initialize data and error arrays
$errors = array();
$data = array();

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
    $data['message'] = 'Successfully retrieved project!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Function for checking user access to selected project
function check_project_access($projectId){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML content As Json Data
    $data['content'] = '';

    //Get user id
    $userId = $_SESSION['$userId'];

    //Query that checks if user has access to selected project
    $query = "SELECT p.id AS id, p.name AS name, p.created_by AS createdBy, p.updated_by AS updatedBy,
              p.date_created AS createdOn, p.date_updated AS updatedOn, p.image As image, 
              p.description AS description, up.role AS role
              FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.id = '$projectId' AND up.user_id = '$userId'";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Hidden Project Id
            $data['content'] .= '<input type="hidden" id="projectHiddenId" name="projectHiddenId" value="'.$row['id'].'">';

            //Project Name
            $data['content'] .= '<div class="row"><div class="col s12 center-align"><h4>'.$row['name'].' '.'#'.$row['id'].'</h4></div></div>';

            //Image
            if(!empty($row['image'])){
                $data['content'] .= '<div class = "card-panel center-align">
                    <img src = "/php-teams/resources/img/uploads/'. $row['image'].'" class = "circle responsive-img">		 
                </div>';
            }

            //Description
            $data['content'] .= '<div class="row card-panel">
                <b>Description</b>
                <p>'.nl2br($row['description']).'</p>
            </div>';

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

            //Get project members
            get_project_members($projectId);

            //Add edit and delete button if user is creator of selected project
            if($row['role'] == 'CREATOR'){
                $data['content'] .= '<div class="row center-align">
                    <a class="modal-action waves-effect btn brand blue" onclick="editProject()">Edit</a>
                    <a class="modal-action waves-effect btn brand red" onclick="deleteProject()">Delete</a>
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

//Function to get project members
function get_project_members($projectId){

    global $connection;
    global $data;
    global $errors;

    //Query that check if user is member of selected project
    $query = "SELECT user_id AS id, user, role FROM user_project_table
              WHERE project_id = '$projectId'
              ORDER BY role ASC, user ASC";
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        //Start table
        $data['content'] .= '<div class="row center-align">
            <table class="highlight">
                <tr>
                    <th>User</th>
                    <th>Role</th>
                </tr>';

        while($row = mysqli_fetch_assoc($result)){

            //Start table row
            $data['content'] .= '<tr style="cursor:pointer" onclick="readUser(\'' . $row['user'] . '\')">';

            //User
            $data['content'] .= '<td>' . $row['user'] . '</td>';

            //Role
            $data['content'] .= '<td>' . $row['role'] . '</td>';

            //Start end table row
            $data['content'] .= '</tr>';
        }
        
        //End table
        $data['content'] .= '</table></div>';
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>