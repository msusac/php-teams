<header class="header-big">
    <nav>
        <div class="nav-wrapper container">
            <a href="/php-teams" class="brand-logo center waves-effect">PHP-Teams</a>
            <a href="#" class="sidenav-trigger waves-effect" data-target="sidenav-hide"><i class="material-icons">menu</i></a>
            <ul class="left hide-on-med-and-down">
                <?php
                if (isset($_SESSION['$user'])) {
                    echo '<li><a class="waves-effect" href="#">Projects</a></li>
                                <li><a class="waves-effect" href="#">Tasks</a></li>
                                <li><a class="waves-effect" href="#">Requests</a></li>';
                    if ($_SESSION['$user_authority'] == 'ROLE_ADMIN') {
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
                    echo '<li><a class="waves-effect" href="#modal-read-user" onclick="readUserById(\'' . $_SESSION['$user_id'] . '\')">' . $_SESSION['$user'] . '</a></li>
                        <li><a class="modal-trigger waves-effect" href="#modal-logout" data-target="modal-user-logout">Logout</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>

    <ul class="sidenav fixed" id="sidenav-hide">
        <li><a class="waves-effect" href="/php-teams">PHP-Teams</a></li>
        <li>
            <div class="divider"></div>
        </li>
        <?php
        if (isset($_SESSION['$user'])) {
            echo '<li><a class="waves-effect" href="#"><i class="material-icons">computer</i> Projects</a></li>
            <li><a class="waves-effect" href="#"><i class="material-icons">assignment</i> Tasks</a></li>
            <li><a class="waves-effect" href="#"><i class="material-icons">notifications</i> Requests</a></li>
            <li><div class="divider"></div></li>';

            if ($_SESSION['$user_authority'] == 'ROLE_ADMIN') {
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
            echo '<li><a class="waves-effect" href="#" onclick="readUserById(\'' . $_SESSION['$user_id'] . '\')"><i class="material-icons">person</i>' . $_SESSION['$user'] . '</a></li>
            <li><a class="modal-trigger waves-effect" href="#modal-logout" data-target="modal-user-logout"><i class="material-icons">exit_to_app</i> Logout</a></li>';
        }
        ?>
        <li>
            <div class="divider"></div>
        </li>
        <li>
        <li><a class="waves-effect" href="/php-teams/about"><i class="material-icons">info</i> About Us</a></li>
        </li>
        <li>
            <div class="divider"></div>
        </li>
        <li class="sidenav-footer">
            <div class="footer-copyright center-align">
                <p>PHP-Teams &copy Mario Sušac <?php echo date('Y') ?></p>
            </div>
            <div class="footer-group-icon center-align">
                <a href="https://github.com/msusac/php-teams" target="_blank" class="svg-link">
                    <img src="/php-teams/resources/img/svg/github-logo.svg" alt="github link" class="circle">
                </a>
                <a href="https://facebook.com" target="_blank" class="svg-link">
                    <img src="/php-teams/resources/img/svg/facebook-logo.svg" alt="facebook link" class="circle">
                </a>
                <a href="https://instagram.com" target="_blank" class="svg-link">
                    <img src="/php-teams/resources/img/svg/instagram-logo.svg" alt="instagram link" class="circle">
                </a>
                <a href="https://twitter.com" target="_blank" class="svg-link">
                    <img src="/php-teams/resources/img/svg/twitter-logo.svg" alt="twitter link" class="circle">
                </a>
                <a href="https://youtube.com" target="_blank" class="svg-link">
                    <img src="/php-teams/resources/img/svg/youtube-logo.svg" alt="youtube link" class="circle">
                </a>
            </div>
        </li>
    </ul>

    <!-- User Read Modal -->
    <?php include(APP_ROOT . 'user/modals/read.php') ?>
    <script type="module" src="/php-teams/resources/js/user/activate.js"></script>
    <script type="module" src="/php-teams/resources/js/user/read.js"></script>

    <!-- User Edit Modal -->
    <?php include(APP_ROOT . 'user/modals/edit.php') ?>
    <script type="module" src="/php-teams/resources/js/user/edit.js"></script>

    <!-- Registration Form Modal -->
    <?php include(APP_ROOT . 'user/modals/register.php') ?>

    <!-- Login Form Modal -->
    <?php include(APP_ROOT . 'user/modals/login.php') ?>

    <!-- Logout Form Modal -->
    <?php include(APP_ROOT . 'user/modals/logout.php') ?>
</header>