<?php
//Start session
ob_start();
session_start();

define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');

if (!isset($_SESSION['$user'])) {
    header('Location: /php-teams');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Teams - Projects</title>

    <?php include(APP_ROOT . 'templates/head.php') ?>
</head>

<body>
    <?php include(APP_ROOT . 'templates/header.php') ?>

    <main class="container">
        <section>
            <div class="row center-align">
                <h4>Projects table</h4>
            </div>
            <div class="row">
                <form class="col s12" method="POST" action="process.php" id="form-projects-search">
                    <div class="row center-align">
                        <div class="input-field col s4">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="validate">
                        </div>
                        <div class="input-field col s4">
                            <label for="name">Creator</label>
                            <input id="creator" name="creator" type="text" class="validate">
                        </div>
                        <div class="input-field col s4">
                            <select name="date" id="date">
                                <option value="" selected>Sort By</option>
                                <option value="DATE_CREATED_ASC">Creation Date - Ascending</option>
                                <option value="DATE_CREATED_DESC">Date Created - Descending</option>
                                <option value="DATE_UPDATED_ASC">Date Updated - Ascending</option>
                                <option value="DATE_UPDATED_DESC">Date Updated - Descending</option>
                            </select>
                        </div>
                    </div>
                    <div class="row center-align">
                        <div class="input-field col s12">
                            <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Search</button>
                            <a class="waves-effect btn brand" id="projects-search-clear-btn">Clear</a>
                            <a class="waves-effect btn brand modal-trigger" data-target="modal-project-add">Add</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <table id="table-projects" class="highlight responsive-table centered">
                    <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Created By</th>
                        <th>Updated By</th>
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

    <!-- Project Add Modal -->
    <?php include(APP_ROOT . 'project/add/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/project/add.js"></script>

    <!-- Project Read Modal -->
    <?php include(APP_ROOT . 'project/read/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/project/read.js"></script>

    <!-- Project Edit Modal -->
    <?php include(APP_ROOT . 'project/edit/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/project/edit.js"></script>
    <script type="module" src="/php-teams/resources/js/project/delete.js"></script>

    <script type="module" src="/php-teams/resources/js/project/search.js"></script>
</body>