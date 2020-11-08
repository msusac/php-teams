<div id="modal-user-login" class="modal">
    <div class="modal-content">
        <h4>Sign In</h4>
        <div class="row">
            <form class="col s12" method="POST" action="process.php" id="form-user-login">
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" class="validate" required>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" class="validate" required>
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Login</button>
                    <a class="modal-action modal-close waves-effect btn brand">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/php-teams/resources/js/user/login.js"></script>