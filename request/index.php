<?php
//Start session
ob_start();
session_start();

define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');

if (!isset($_SESSION['$user'])) {
    header('Location: /php-teams');
}

//Check connection with database
include('../config/db_connect.php');

//Get user id
$userId = $_SESSION['$userId'];

//Project selection 
$projectSelect = array();

//Query to get all projects that belong to user
$query = "SELECT p.id AS id, p.name AS name
          FROM project_table p
          INNER JOIN user_project_table up ON up.project_id = p.id
          WHERE up.user_id = '$userId' AND up.role = 'CREATOR'
          ORDER BY p.name ASC";

//Execute query
$result = mysqli_query($connection, $query);

//Check row
if($result){
    //Fetch rows
    while($row = mysqli_fetch_assoc($result)){
        $projectSelect[] = array('id' => $row['id'], 'name' => $row['name']);
    }
}

//User selection
$userSelect = array();

//Query to get all users
$query = "SELECT u.id AS id, u.username AS name
          FROM user_table u
          INNER JOIN user_authority_table ua ON ua.user_id = u.id
          INNER JOIN authority_table a ON a.id = ua.authority_id
          WHERE u.id != '$userId' AND a.name IN ('ROLE_ADMIN', 'ROLE_USER')
          ORDER BY username ASC";

//Execute query
$result = mysqli_query($connection, $query);

//Check row
if($result){
    //Fetch rows
    while($row = mysqli_fetch_assoc($result)){
        $userSelect[] = array('id' => $row['id'], 'name' => $row['name']);
    }
}

//Close connection
mysqli_close($connection);
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Teams - Requests</title>

    <?php include(APP_ROOT . 'templates/head.php') ?>
</head>

<body>
    <?php include(APP_ROOT . 'templates/header.php') ?>

    <main class="container">
        <section id="section-table">
            <div class="row center-align">
                <h4>Requests table</h4>
            </div>
            <div class="row">
                <form class="col s12" method="POST" action="process.php" id="form-requests-search">
                    <div class="row center-align">
                        <div class="input-field col s3">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="validate">
                        </div>
                        <div class="input-field col s3">
                            <select name="status" id="status">
                                <option value="" selected>Status</option>
                                <option value="PENDING">Pending</option>
                                <option value="ACCEPTED">Accepted</option>
                                <option value="REJECTED">Rejected</option>
                            </select>
                        </div>
                        <div class="input-field col s3">
                            <select name="date" id="date">
                                <option value="" selected>Sort By - Date</option>
                                <option value="DATE_SEND_ASC">Date Send - Ascending</option>
                                <option value="DATE_SEND_DESC">Date Send - Descending</option>
                                <option value="DATE_REPLY_ASC">Date Reply - Ascending</option>
                                <option value="DATE_REPLY_DESC">Date Reply - Descending</option>
                            </select>
                        </div>
                        <div class="input-field col s3">
                            <select name="mailbox" id="mailbox">
                                <option value="" selected>Mailbox</option>
                                <option value="INBOX">Inbox</option>
                                <option value="OUTBOX">Outbox</option>
                            </select>
                        </div>
                    </div>
                    <div class="row center-align">
                        <div class="input-field col s12">
                            <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Search</button>
                            <a class="waves-effect btn brand" id="requests-search-clear-btn">Clear</a>
                            <a class="waves-effect btn brand modal-trigger" data-target="modal-request-add">Add</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row center-align">
                <h4><span id="total-reg"></span></h4>
            </div>
            <div class="row center-align">
                <div class="col-md-12 center text-center">
                    <ul class="pagination pager"></ul>
                </div>
            </div>
            <div class="row">
                <table id="table-requests" class="highlight responsive-table centered">
                    <thead>
                        <th>#</th>
                        <th>Project</th>
                        <th>Name</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Sent On</th>
                        <th>Replied On</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include(APP_ROOT . 'templates/footer.php') ?>
    </main>

    <!-- Request Add Modal -->
    <?php include(APP_ROOT . 'request/add/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/request/add.js"></script>

    <!-- Request Read Modal -->
    <?php include(APP_ROOT . '/request/read/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/request/read.js"></script>
    <script type="module" src="/php-teams/resources/js/request/reply.js"></script>

    <script type="module" src="/php-teams/resources/js/request/search.js"></script>
</body>