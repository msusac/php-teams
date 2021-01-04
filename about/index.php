<?php
//Start session
ob_start();
session_start();

define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"] . '/php-teams/');
?>

<!DOCTYPE html>
<html>

<head>
    <title>PHP Teams - About Us</title>

    <?php include(APP_ROOT . 'templates/head.php') ?>
</head>

<body>
    <?php include(APP_ROOT . 'templates/header.php') ?>

    <main class="container">
        <div class="row">
            <section>
                <div class="row center-align">
                    <h4>About PHP-Teams</h4>
                    <div class="col s12 l4">
                        <div class="icon-block">
                            <h2 class="center light-blue-text"><i class="material-icons">flash_on</i></h2>
                            <h5 class="center">Speeds up development</h5>

                            <p class="light">Fusce id lacus dapibus, auctor felis et, vehicula sapien. Nullam quis venenatis lorem, vel aliquet quam. Donec nec sapien faucibus, sollicitudin ante eget, aliquam justo. Vestibulum fermentum sit amet dui eu varius. Fusce suscipit luctus finibus. Quisque tincidunt, nisl in pulvinar ultricies, tellus arcu ornare nisi, vel luctus lectus sapien et odio. Proin maximus, dui et interdum tempus, ligula nisl commodo enim, eget mattis libero nisi vel sem. Fusce cursus arcu lorem, nec faucibus neque consequat gravida. Nulla non ex et libero volutpat fermentum. Donec efficitur consequat laoreet. Mauris quis felis varius tellus auctor sagittis.</p>
                        </div>
                    </div>

                    <div class="col s12 l4">
                        <div class="icon-block">
                            <h2 class="center light-blue-text"><i class="material-icons">group</i></h2>
                            <h5 class="center">User Experience Focused</h5>

                            <p class="light">Fusce id lacus dapibus, auctor felis et, vehicula sapien. Nullam quis venenatis lorem, vel aliquet quam. Donec nec sapien faucibus, sollicitudin ante eget, aliquam justo. Vestibulum fermentum sit amet dui eu varius. Fusce suscipit luctus finibus. Quisque tincidunt, nisl in pulvinar ultricies, tellus arcu ornare nisi, vel luctus lectus sapien et odio. Proin maximus, dui et interdum tempus, ligula nisl commodo enim, eget mattis libero nisi vel sem. Fusce cursus arcu lorem, nec faucibus neque consequat gravida. Nulla non ex et libero volutpat fermentum. Donec efficitur consequat laoreet. Mauris quis felis varius tellus auctor sagittis.</p>
                        </div>
                    </div>

                    <div class="col s12 l4">
                        <div class="icon-block">
                            <h2 class="center light-blue-text"><i class="material-icons">settings</i></h2>
                            <h5 class="center">Easy to work with</h5>

                            <p class="light">Fusce id lacus dapibus, auctor felis et, vehicula sapien. Nullam quis venenatis lorem, vel aliquet quam. Donec nec sapien faucibus, sollicitudin ante eget, aliquam justo. Vestibulum fermentum sit amet dui eu varius. Fusce suscipit luctus finibus. Quisque tincidunt, nisl in pulvinar ultricies, tellus arcu ornare nisi, vel luctus lectus sapien et odio. Proin maximus, dui et interdum tempus, ligula nisl commodo enim, eget mattis libero nisi vel sem. Fusce cursus arcu lorem, nec faucibus neque consequat gravida. Nulla non ex et libero volutpat fermentum. Donec efficitur consequat laoreet. Mauris quis felis varius tellus auctor sagittis.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include(APP_ROOT . 'templates/footer.php') ?>
</body>

</html>