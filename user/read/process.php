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

//Check request
if (isset($_POST['username']) && !empty($_POST['username']))
    get_user_by_username($_POST['username']);
else
    $errors['sql'] = 'User does not exists!';

//Check if there are any errors
if (!empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
} else {
    $data['success'] = true;
    $data['message'] = 'Successfully retrieved user!';
}

//Return all data to an AJAX call
echo json_encode($data);

//Get User by Username
function get_user_by_username($username)
{
    global $connection;
    global $data;
    global $errors;

    //Initializing HTML table As Json Data
    $data['table'] = '';

    //Query that check if user exists in database
    $query = "SELECT u.username AS username, u.fullname AS fullname, u.email AS email, u.date_created AS dateCreated, 
              a.name AS role 
              FROM user_table u 
              LEFT OUTER JOIN user_authority_table ua ON ua.user_id = u.id 
              LEFT OUTER JOIN authority_table a ON a.id = ua.authority_id 
              WHERE u.username = '$username'";

    //Execute query
    $result = mysqli_query($connection, $query);

    //Check result
    if($result){
        $row = mysqli_fetch_assoc($result);

        //Check if row is empty or not
        if(!empty($row)){

            //Username
            $data['table'] .=
                '<tr>
                     <td><b>Username</b></td>
                     <td><i>' . $row['username'] . '</i></td>
                </tr>';

            //Fullname
            if (!empty($row['fullname'])) {
                $data['table'] .=
                    '<tr>
                        <td><b>Fullname</b></td>
                        <td><i>' . $row['fullname'] . '</i></td>
                    </tr>';
            } else {
                $data['table'] .=
                    '<tr>
                        <td><b>Fullname</b></td>
                        <td><i>None</i></td>
                    </tr>';
            }

            //Email address
            $data['table'] .=
                '<tr>
                    <td><b>E-mail address</b></td>
                    <td><i>' . $row['email'] . '</i></td>
                </tr>';

            //Joined at
            $date = strtotime($row['dateCreated']);

            $data['table'] .=
                '<tr>
                    <td><b>Joined at</b></td>
                    <td><i>' . date("d/m/Y H:i", $date) . '</i></td>
                </tr>';

            //Role
            if ($row['role'] == 'ROLE_ADMIN') {
                $data['table'] .=
                    '<tr>
                        <td><b>Role</b></td>
                        <td><i>Admin</i></td>
                    </tr>';
            } else if ($row['role'] == 'ROLE_USER') {
                $data['table'] .=
                    '<tr>
                        <td><b>Role</b></td>
                        <td><i>User</i></td>
                    </tr>';
            } else {
                $data['table'] .=
                    '<tr>
                        <td><b>Role</b></td>
                        <td><i>Not Activated</i></td>
                    </tr>';
            }

            //User actions
            $data['table'] .= '<tr><td><b>Actions</b></td><td>';

            //Check if profile belongs to logged user
            if ($_SESSION['$user'] == $row['username'])
                $data['table'] .='<a class="modal-action waves-effect btn brand blue" href="#" onclick="editUser()">Edit</a>';

            //Add button for activating user, ADMIN ONLY
            if ($_SESSION['$userRole'] == 'ROLE_ADMIN' && empty($row['role'])){
                $data['table'] .='<a class="modal-action waves-effect btn brand green" href="#" onclick="activateUser(\'' . $row['username'] . '\')">Activate</a>';
            }
            

            $data['table'] .= '</td></tr>';
        }
    }
    else{
        $errors['sql'] = mysqli_error($connection);
    }
}

//Close connection
mysqli_close($connection);
?>