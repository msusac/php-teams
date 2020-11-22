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
$creator = prepare_field($_POST['creator']);
$date = prepare_field($_POST['date']);

//Function for searching projects
get_projects_by_search($name, $creator, $date);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Search sucessful!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[;,<>&=%:'“]/i", "", $field));
}

//Function for searching projects
function get_projects_by_search($name, $creator, $date){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML table
    $data['table'] = '';

    //Initialize order by query
    $query_order_by = "";

    //Initialize date array
    $dateArray = array('DATE_CREATED_ASC', 'DATE_CREATED_DESC', 'DATE_UPDATED_ASC', 'DATE_UPDATED_DESC');

    //Check sort-by date
    if($date == $dateArray[0]){
        $query_order_by = "ORDER BY date_created ASC";
    }
    else if($date == $dateArray[1]){
        $query_order_by = "ORDER BY date_created DESC";
    }
    else if($date == $dateArray[2]){
        $query_order_by = "ORDER BY date_updated ASC";
    }
    else if($date == $dateArray[3]){
        $query_order_by = "ORDER BY date_updated DESC";
    }

    //Get user id
    $userId = $_SESSION['$userId'];
    
    //Query that searches projects
    $query = "SELECT p.id AS id, p.name AS name, p.created_by AS createdBy, p.updated_by AS updatedBy,
              p.date_created AS createdOn, p.date_updated AS updatedOn
              FROM project_table p
              INNER JOIN user_project_table up ON up.project_id = p.id
              WHERE p.name LIKE '%$name%' AND p.created_by LIKE '%$creator%' AND  up.user_id = '$userId'"
              . $query_order_by;

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        //Fetch rows
        while($row = mysqli_fetch_assoc($result)){

            //Start table row
            $data['table'] .= '<tr onclick="readProject(\'' . $row['id'] . '\')">';

            //Project id
            $data['table'] .= '<td>' . $row['id'] . '</td>';

            //Project name
            $data['table'] .= '<td>' . $row['name'] . '</td>';

            //Created By
            $data['table'] .= '<td>' . $row['createdBy'] . '</td>';

            //Updated By
            if(!empty($row['updatedBy'])){
                $data['table'] .= '<td>' . $row['updatedBy'] . '</td>';
            }
            else{
                $data['table'] .= '<td>None</td>';
            }

            //Created On
            $date = strtotime($row['createdOn']);

            $data['table'] .= '<td>' . date("d/m/Y H:i", $date) . '</td>';

            //Updated On
            if(!empty($row['updatedOn'])){
                $date = strtotime($row['updatedOn']);

                $data['table'] .= '<td>' . date("d/m/Y H:i", $date) . '</td>';
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
