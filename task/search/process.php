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

//Preparing fields
$name = prepare_field($_POST['name']);
$projectId = prepare_field($_POST['project']);
$status = prepare_field($_POST['status']);
$date = prepare_field($_POST['date']); 

//Function for searching tasks
get_project_tasks_by_search($name, $projectId, $status, $date);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Search sucessfull!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[<>&=%:'“]/i", "", $field));
}

//Function for searching project tasks
function get_project_tasks_by_search($name, $projectId, $status, $date){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML table
    $data['table'] = '';

    //Initialize order by query
    $query_order_by = "";

    //Initialize where project task status
    $query_status_where = "";

    //Initialize where project id
    $query_project_id_where = "";

    //Check sort-by date
    if($date == 'DATE_CREATED_ASC'){
        $query_order_by = "ORDER BY t.date_created ASC";
    }
    else if($date == 'DATE_CREATED_DESC'){
        $query_order_by = "ORDER BY t.date_created DESC";
    }
    else if($date == 'DATE_UPDATED_ASC'){
        $query_order_by = "ORDER BY t.date_updated ASC";
    }
    else if($date == 'DATE_UPDATED_DESC'){
        $query_order_by = "ORDER BY t.date_updated DESC";
    }

    //Check project id
    if(!empty($projectId)){
        $query_project_id_where = "AND t.project_id = '$projectId'";
    }

    //Check project status
    if(!empty($status)){
        $query_status_where = "AND t.status = '$status'";
    }

    //Get user id
    $userId = $_SESSION['$user_id'];
    
    //Query that check if user exists in database
    $query = "SELECT t.id AS id, t.name AS task, t.status AS status,
              t.date_created AS createdOn, t.date_updated AS updatedOn,
              p.name AS project, p.id AS projectId
              FROM task_table t
              INNER JOIN project_table p ON p.id = t.project_id
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE up.user_id = '$userId' AND t.name LIKE '%$name%'"
              . $query_project_id_where
              . $query_status_where
              . $query_order_by;

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check row
    if($result){
        //Fetch rows
        while($row = mysqli_fetch_assoc($result)){

            //Start table row
            $data['table'] .= '<tr onclick="readTask(\'' . $row['id'] . '\')">';

            //Task id
            $data['table'] .= '<td>' . $row['id'] . '</td>';

            //Project name
            $data['table'] .= '<td>' . $row['project'] . ' - ' . $row['projectId'] . '</td>';

            //Task name
            $data['table'] .= '<td>' . $row['task'] . '</td>';

            //Task Status
            $status = preg_replace("/_/i", " ", $row['status']);

            $data['table'] .= '<td>' . $status . '</td>';

            //Created On
            $data['table'] .= '<td>' . $row['createdOn'] . '</td>';

            //Updated On
            if(!empty($row['updatedOn'])){
                $data['table'] .= '<td>' . $row['updatedOn'] . '</td>';
            }
            else{
                $data['table'] .= '<td>None</td>';
            }

            //Start end table row
            $data['table'] .= '</tr>';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>
