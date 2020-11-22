<?php
//Start session
ob_start();
session_start();

define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');

if ($_SESSION['$userRole'] != 'ROLE_ADMIN') {
    header('Location: /php-teams');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Teams - Users</title>

    <?php include(APP_ROOT . 'templates/head.php') ?>
</head>

<body>
    <?php include(APP_ROOT . 'templates/header.php') ?>

    <main class="container">
        <section id="section-table">
            <div class="row center-align">
                <h4>Users table</h4>
            </div>
            <div class="row">
                <form class="col s12" method="POST" action="process.php" id="form-users-search">
                    <div class="row center-align">
                        <div class="input-field col s4">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" class="validate">
                        </div>
                        <div class="input-field col s4">
                            <label for="email">E-mail</label>
                            <input id="email" name="email" type="text" class="validate">
                        </div>
                        <div class="input-field col s4">
                            <select name="role" id="role">
                                <option value="" selected>Role not selected</option>
                                <option value="ADMIN">Admin</option>
                                <option value="USER">User</option>
                                <option value="NOT_ACTIVATED">Not Activated</option>
                            </select>
                        </div>
                        <div class="row center-align">
                            <div class="input-field col s12">
                                <button type="submit" name="submit" value="submit" class="waves-effect btn brand">Search</button>
                                <a class="waves-effect btn brand" id="users-search-clear-btn">Clear</a>
                            </div>
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
                <table id="table-users" class="highlight responsive-table centered">
                    <thead>
                        <th>Username</th>
                        <th>E-mail</th>
                        <th>Role</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include(APP_ROOT . 'templates/footer.php') ?>
    </main>

    <script type="module" src="/php-teams/resources/js/user/search.js"></script>
</body>