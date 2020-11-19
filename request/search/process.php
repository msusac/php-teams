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
$status = prepare_field($_POST['status']);
$date = prepare_field($_POST['date']);
$mailbox = prepare_field($_POST['mailbox']); 

//Function for searching requests
get_requests_by_search($name, $status, $date, $mailbox);

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

//Function for searching project tasks
function get_requests_by_search($name, $status, $date, $mailbox){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML table
    $data['table'] = '';

    //Initialize order by query 
    $query_order_by = "";

    //Initialize where mailbox
    $query_where_mailbox = "";

    //Initialize where request status
    $query_where_status = "";

    //Check sort-by date
    if($date == 'DATE_SEND_ASC'){
        $query_order_by .= "ORDER BY r.date_send ASC ";
    }
    else if($date == 'DATE_SEND_DESC'){
        $query_order_by .= "ORDER BY r.date_send DESC ";
    }
    else if($date == 'DATE_REPLY_ASC'){
        $query_order_by .= "ORDER BY r.date_reply ASC ";
    }
    else if($date == 'DATE_REPLY_DESC'){
        $query_order_by .= "ORDER BY r.date_reply DESC ";
    }

    //Get user id
    $userId = $_SESSION['$userId'];

    //Check mailbox
    if($mailbox == 'INBOX'){
        $query_where_mailbox = "AND r.user_to_id = '$userId' ";
    }
    else if($mailbox == 'OUTBOX'){
        $query_where_mailbox = "AND r.user_from_id = '$userId' ";
    }
    else{
        $query_where_mailbox = "AND (r.user_from_id = '$userId' OR r.user_to_id = '$userId') ";
    }

    //Check request status
    if(in_array($status, array('PENDING', 'ACCEPTED', 'REJECTED'))){
        $query_where_status = "AND r.status = '$status' ";
    }

    //Query to search request
    $query = "SELECT r.id AS id, r.name AS request, r.status AS status, p.name AS project, p.id AS projectId, 
              r.user_from_id AS userFromId, r.user_to_id AS userToId, r.date_send AS sendOn, r.date_reply AS replyOn
              FROM request_table r
              INNER JOIN project_table p ON p.id = r.project_id
              WHERE r.name LIKE '%$name%' "
              .$query_where_status
              .$query_where_mailbox
              .$query_order_by;
    
    //Execute query
    $result = mysqli_query($connection, $query);

    //Check row
    if($result){
        //Fetch rows
        while($row = mysqli_fetch_assoc($result)){

            //Start table row
            $data['table'] .= '<tr onclick="readRequest(\'' . $row['id'] . '\')">';

            //Task id
            $data['table'] .= '<td>' . $row['id'] . '</td>';

            //Project name
            $data['table'] .= '<td>' . $row['project'] . ' - ' . $row['projectId'] . '</td>';

            //Request name
            $data['table'] .= '<td>' . $row['request'] . '</td>';

            //User From
            $userFrom = get_username($row['userFromId']);

            $data['table'] .= '<td>' . $userFrom . '</td>';

            //User To
            $userTo = get_username($row['userToId']);

            $data['table'] .= '<td>' . $userTo . '</td>';

            //Task Status
            $status = preg_replace("/_/i", " ", $row['status']);

            $data['table'] .= '<td>' . $status . '</td>';

            //Send On
            $date = strtotime($row['sendOn']);

            $data['table'] .= '<td>' . date("d/m/Y H:i", $date) . '</td>';

            //Updated On
            if(!empty($row['replyOn'])){
                $date = strtotime($row['replyOn']);

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
