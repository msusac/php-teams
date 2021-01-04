<?php
//Start session
ob_start();
session_start();

define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');
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
            <div class="row">
                <div class="slider">
                    <ul class="slides">
                        <li>
                            <img src="/php-teams/resources/img/welcome.jpg">
                            <div class="caption center-align card">
                                <div class="card-content">
                                    <h3 span="card-title">Welcome to PHP-Teams!</h3>
                                    <h5 class="light text-lighten-3">Phasellus laoreet leo vitae orci molestie pellentesque. Nulla finibus blandit augue, eget condimentum felis ullamcorper eu. Aliquam erat volutpat..</h5>
                                </div>
                            </div>
                        </li>
                        <li>
                            <img src="/php-teams/resources/img/project.png">
                            <div class="caption left-align card">
                                <div class="card-content">
                                    <h3 span="card-title">Start your own projects!</h3>
                                    <h5 class="light text-lighten-3">Suspendisse molestie felis et leo consequat dictum. Nulla risus lectus, eleifend eu ligula et, sodales pellentesque nulla. Donec ultrices quis orci quis consectetur.</h5>
                                </div>
                            </div>
                        </li>
                        <li>
                            <img src="/php-teams/resources/img/task.jpg">
                            <div class="caption right-align card">
                                <div class="card-content">
                                    <h3 span="card-title">Manage your tasks!</h3>
                                    <h5 class="light text-lighten-3"> Nullam tristique lobortis velit vel facilisis. Suspendisse ac tincidunt quam. Curabitur lobortis eleifend metus eu posuere. Proin id pellentesque sem.</h5>
                                </div>
                            </div>
                        </li>
                        <li>
                            <img src="/php-teams/resources/img/team.png">
                            <div class="caption center-align card">
                                <div class="card-content">
                                    <h3 span="card-title">Find your own team!</h3>
                                    <h5 class="light text-lighten-3">Fusce vulputate lacinia nulla, in fermentum leo bibendum ut. Integer elementum venenatis arcu, a tincidunt risus dictum eget. In a elit id diam congue vehicula sit amet ac lorem. Nunc id iaculis arcu, ut lobortis lorem.</h5>
                                </div>
                            </div>
                        </li>
                        <li>
                            <img src="/php-teams/resources/img/start.png">
                            <div class="caption center-align card">
                                <div class="card-content">
                                    <h3 span="card-title">Get Started Today!</h3>
                                    <h5 class="light text-lighten-3">Etiam efficitur a mi non sagittis. Etiam eleifend id sapien et viverra. Ut vel ex gravida, egestas sem et, vestibulum nisl.</h5>
                                </div>
                                <?php
                                    if(!isset($_SESSION['$user'])){
                                        echo '<div class="card-action">
                                        <a class="modal-trigger waves-effect" href="#modal-register" data-target="modal-user-register">Register</a>
                                        <a class="modal-trigger waves-effect" href="#modal-login" data-target="modal-user-login">Login</a>
                                    </div>';
                                    }
                                ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </main>

    <?php include(APP_ROOT . 'templates/footer.php') ?>
</body>

</html>