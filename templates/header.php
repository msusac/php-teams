<header class="container">
    <nav>
        <div class="nav-wrapper">
            <a href="/php-teams" class="brand-logo center waves-effect">PHP-Teams</a>
            <a href="#" class="sidenav-trigger waves-effect" data-target="sidenav-hide"><i class="material-icons">menu</i></a>
            <ul class="left hide-on-med-and-down">
                <?php
                if (isset($_SESSION['$user'])) {
                    echo '<li><a class="waves-effect" href="/php-teams/project">Projects</a></li>
                                <li><a class="waves-effect" href="#">Tasks</a></li>
                                <li><a class="waves-effect" href="#">Requests</a></li>';
                    if ($_SESSION['$user_role'] == 'ROLE_ADMIN') {
                        echo '<li><a class="waves-effect" href="/php-teams/user">Users</a></li>';
                    }
                }
                ?>
            </ul>
            <ul class="right hide-on-med-and-down">
                <?php
                if (!isset($_SESSION['$user'])) {
                    echo '<li><a class="modal-trigger waves-effect" href="#modal-login" data-target="modal-user-login">Login</a></li>
                    <li><a class="modal-trigger waves-effect" href="#modal-register" data-target="modal-user-register">Register</a></li>';
                } else {
                    echo '<li><a class="waves-effect" href="#modal-read-user" onclick="readUser(\'' . $_SESSION['$user'] . '\')">'. $_SESSION['$user'] .'</a></li>
                        <li><a class="modal-trigger waves-effect" href="#modal-logout" data-target="modal-user-logout">Logout</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <ul class="sidenav" id="sidenav-hide">
        <li><a class="waves-effect" href="/php-teams">PHP-Teams</a></li>
        <li>
            <div class="divider"></div>
        </li>
        <?php
        if (isset($_SESSION['$user'])) {
            echo '<li><a class="waves-effect" href="/php-teams/project"><i class="material-icons">computer</i> Projects</a></li>
            <li><a class="waves-effect" href="#"><i class="material-icons">assignment</i> Tasks</a></li>
            <li><a class="waves-effect" href="#"><i class="material-icons">notifications</i> Requests</a></li>
            <li><div class="divider"></div></li>';

            if ($_SESSION['$user_role'] == 'ROLE_ADMIN') {
                echo '<li><a class="waves-effect" href="/php-teams/user"><i class="material-icons">group</i> Users</a></li>
                <li><div class="divider"></div></li>';
            }
        }
        ?>
        <?php
        if (!isset($_SESSION['$user'])) {
            echo '<li><a class="modal-trigger waves-effect" href="#modal-login" data-target="modal-user-login"><i class="material-icons">keyboard_return</i> Login</a></li>
            <li><a class="modal-trigger waves-effect" href="#modal-register" data-target="modal-user-register"><i class="material-icons">person_add</i> Register</a></li>';
        } else {
            echo '<li><a class="waves-effect" href="#" onclick="readUser(\'' . $_SESSION['$user'] . '\');"><i class="material-icons">person</i>' . $_SESSION['$user'] . '</a></li>
            <li><a class="modal-trigger waves-effect" href="#modal-logout" data-target="modal-user-logout"><i class="material-icons">exit_to_app</i> Logout</a></li>';
        }
        ?>
        <li>
            <div class="divider"></div>
        </li>
        <li class="footer-copyright">
            <div class="row">
                <div class="center black-text">
                    <p>PHP-Teams Copyright 2020&copy</p>
                </div>
            </div>
        </li>
    </ul>

    <!-- User Read Modal -->
    <?php include(APP_ROOT . 'user/read/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/user/activate.js"></script>
    <script type="module" src="/php-teams/resources/js/user/read.js"></script>

    <!-- User Edit Modal -->
    <?php include(APP_ROOT . 'user/edit/modal.php') ?>
    <script type="module" src="/php-teams/resources/js/user/edit.js"></script>

    <!-- Registration Form Modal -->
    <?php include(APP_ROOT . 'user/register/modal.php') ?>

    <!-- Login Form Modal -->
    <?php include(APP_ROOT . 'user/login/modal.php') ?>

    <!-- Logout Form Modal -->
    <?php include(APP_ROOT . 'user/logout/modal.php') ?>
</header>