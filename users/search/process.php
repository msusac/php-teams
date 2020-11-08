<?php
//Check if access is from AJAX/JS Scripts
include('../../config/ajax_connect.php');

//Check connection with database
include('../../config/db_connect.php');

//Initialize data and error arrays
$errors = array();
$data = array();

//Preparing fields
$username = prepare_field($_POST['username']);
$email = prepare_field($_POST['email']);
$role = prepare_field($_POST['role']);

get_users_by_search($username, $email, $role);

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Registration sucessfull! Your account will be activated soon!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Preparing fields for database without SQL Injection
function prepare_field($field)
{
    return trim(preg_replace("/[;,<>&=%:'“]/i", "", $field));
}

function get_users_by_search($username, $email, $role){

    global $connection;
    global $data;
    global $errors;

    //Initializing HTML table
    $data['table'] = '';

    //Initialize where query
    $query_where = "WHERE username LIKE '%$username%' AND email LIKE '%$email%'";

    //Check if role is admin, user or null
    if($role == 'ADMIN'){
        $query_where = "WHERE username LIKE '%$username%' AND email LIKE '%$email%' AND a.name = 'ROLE_ADMIN'";
    }
    else if($role == 'USER'){
        $query_where = "WHERE username LIKE '%$username%' AND email LIKE '%$email%' AND a.name = 'ROLE_USER'";
    }
    else if($role == 'NOT_ACTIVATED'){
        $query_where = "WHERE username LIKE '%$username%' AND email LIKE '%$email%' AND a.name IS null";
    }

    //Query that check if user exists in database
    $query = "SELECT u.username AS username, u.fullname AS fullname, u.email AS email, u.created_at AS createdAt, 
              a.name AS role 
              FROM user_table u 
              LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
              LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id
              ".$query_where."
              ORDER BY username ASC";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check row
    if($result){
        //Fetch rows
        while($row = mysqli_fetch_assoc($result)){

            //Start table row
            $data['table'] .= '<tr>';

            //Username
            $data['table'] .= '<td>' . $row['username'] . '</td>';

            //E-mail address
            $data['table'] .= '<td>' . $row['email'] . '</td>';

            //Role
            if ($row['role'] == 'ROLE_ADMIN') {
                $data['table'] .= '<td>Admin</td>';
            } else if ($row['role'] == 'ROLE_USER') {
                $data['table'] .= '<td>User</td>';
            } else {
                $data['table'] .= '<td>Not Activated</td>';
            }

            //Add read button
            $data['table'] .= '<td><a class="modal-action waves-effect btn brand" href="#" onclick="readUser(\'' . $row['username'] . '\')">Read</a></td>';

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
