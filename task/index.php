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

//Project selection 
$projectSelect = array();

//Get user id
$userId = $_SESSION['$userId'];

//Query to get all projects associated with user
$query = "SELECT p.id AS id, p.name AS name
          FROM project_table p
          INNER JOIN user_project_table up ON up.project_id = p.id
          WHERE up.user_id = '$userId'
          ORDER BY p.name ASC";

//Execute query
$result = mysqli_query($connection, $query);

//Check row
if ($result) {
    //Fetch rows
    while ($row = mysqli_fetch_assoc($result)) {
        $projectSelect[] = array('id' => $row['id'], 'name' => $row['name']);
    }
}

//Close connection
mysqli_close($connection);
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Teams - Projects Tasks</title>

    <?php include(APP_ROOT . 'templates/head.php') ?>
</head>

<body>
    <?php include(APP_ROOT . 'templates/header.php') ?>

    <main class="container">
        <section id="section-table">
            <div class="row center-align">
                <h4>Projects Tasks table</h4>
            </div>
            <div class="row">
                <form class="col s12" method="POST" action="process.php" id="form-tasks-search">
                    <div class="row center-align">
                        <div class="input-field col s3">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="validate">
                        </div>
                        <div class="input-field col s3">
                            <select name="project" id="project">
                                <option value="" selected>Select Project</option>
                                <?php
                                //Prepare options fields
                                foreach ($projectSelect as $project) {
                                    echo '<option value="' . $project['id'] . '">' . $project['name'] . ' - ' . $project['id'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-field col s2">
                            <select name="status" id="status">
                                <option value="" selected>Status</option>
                                <option value="NOT_STARTED">Not Started</option>
                                <option value="IN_PROGRESS">In Progress</option>
                                <option value="DONE">Done</option>
                                <option value="REVERSED">Reversed</option>
                            </select>
                        </div>
                        <div class="input-field col s2">
                            <select name="date" id="date">
                                <option value="" selected>Sort By - Date</option>
                                <option value="DATE_CREATED_ASC">Date Created - Ascending</option>
                                <option value="DATE_CREATED_DESC">Date Created - Descending</option>
                                <option value="DATE_UPDATED_ASC">Date Updated - Ascending</option>
                                <option value="DATE_UPDATED_DESC">Date Updated - Descending</option>
                            </select>
                        </div>
                        <div class="input-field col s2">
                            <select name="days" id="days">
                                <option value="" selected>Sort By - Days Remaining</option>
                                <option value="DAYS_START_ASC">Days Start - Ascending</option>
                                <option value="DAYS_START_DESC">Days Start - Descending</option>
                                <option value="DAYS_END_ASC">Days End - Ascending</option>
                                <option value="DAYS_END_DESC">Days End - Descending</option>
                                <option value="EXPIRED">Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="row center-align">
                        <div class="input-field col s12">
                            <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Search</button>
                            <a class="waves-effect btn brand" id="tasks-search-clear-btn">Clear</a>
                            <a class="waves-effect btn brand modal-trigger" data-target="modal-task-add">Add</a>
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
                <table id="table-tasks" class="highlight responsive-table centered">
                    <thead>
                        <th>#</th>
                        <th>Project</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Days Remaining</th>
                        <th>Created On</th>
                        <th>Updated On</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include(APP_ROOT . 'templates/footer.php') ?>
    </main>

    <!-- Project Task Add Modal -->
    <?php include(APP_ROOT . '/task/add/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/task/add.js"></script>

    <!-- Project Task Read Modal -->
    <?php include(APP_ROOT . '/task/read/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/task/read.js"></script>
    <script type="module" src="/php-teams/resources/js/task/delete.js"></script>

    <!-- Project Task Edit Modal -->
    <?php include(APP_ROOT . '/task/edit/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/task/edit.js"></script>

    <script type="module" src="/php-teams/resources/js/task/search.js"></script>
</body>