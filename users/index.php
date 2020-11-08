<?php
ob_start();
session_start();

define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');

if ($_SESSION['$user_role'] != 'ROLE_ADMIN') {
    header('Location: /php-teams');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Teams - Main Page</title>

    <?php include(APP_ROOT . 'templates/head.php') ?>
</head>

<body>
    <?php include(APP_ROOT . 'templates/header.php') ?>

    <main class="container">
        <section>
            <div class="row center-align">
                <h5>User table</h5>
            </div>
            <div class="row">
                <form class="col s12" method="POST" action="process.php" id="form-users-search">
                    <div class="row center-align">
                        <div class="input-field col s3">
                            <label for="username">Username</label>
                            <input id="username" name="username" type="text" class="validate">
                        </div>
                        <div class="input-field col s3">
                            <label for="email">E-mail</label>
                            <input id="email" name="email" type="text" class="validate">
                        </div>
                        <div class="input-field col s3">
                            <select name="role" id="role">
                                <option value="" selected>Role not selected</option>
                                <option value="ADMIN">Admin</option>
                                <option value="USER">User</option>
                                <option value="NOT_ACTIVATED">Not Activated</option>
                            </select>
                        </div>
                        <div class="input-field col s3">
                            <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Search</button>
                            <a class="modal-action waves-effect btn brand" id="users-search-clear-btn">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <table id="table-users" class="highlight responsive-table">
                    <thead>
                        <th>Username</th>
                        <th>E-mail</th>
                        <th>Role</th>
                        <th></th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include(APP_ROOT . 'templates/footer.php') ?>
    </main>

    <script src="/php-teams/resources/js/users/search.js"></script>
</body>