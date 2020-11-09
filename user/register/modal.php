<div id="modal-user-register" class="modal">
    <div class="modal-content">
        <h4>Sign Up</h4>
        <div class="row">
            <form class="col s12" method="POST" action="process.php" id="form-user-register">
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" class="validate" required>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="email">E-mail Address</label>
                        <input id="email" name="email" type="email" class="validate" required>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" class="validate" required>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="password_repeat">Repeat Password</label>
                        <input id="password_repeat" name="password_repeat" type="password" class="validate" required>
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Register</button>
                    <a class="modal-action waves-effect btn brand" id="register-clear-btn">Clear</a>
                    <a class="modal-action modal-close waves-effect btn brand">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/php-teams/resources/js/user/register.js"></script>