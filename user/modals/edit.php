<div id="modal-user-edit" class="modal">
    <div class="modal-content">
        <h4>Edit user details</h4>
        <div class="row">
            <form class="col s12" method="POST" action="process.php" id="form-user-edit">
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="fullname">Fullname (Optional)</label>
                        <input id="fullname" name="fullname" type="text" class="validate">
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="password_old">Old Password</label>
                        <input id="password_old" name="password_old" type="password" class="validate">
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="password_new">New Password</label>
                        <input id="password_new" name="password_new" type="password" class="validate">
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="password_new_confirm">Confirm New Password</label>
                        <input id="password_new_confirm" name="password_new_confirm" type="password" class="validate">
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Edit</button>
                    <a class="modal-action waves-effect btn brand" id="btn-user-edit-clear">Clear</a>
                    <a class="modal-action modal-close waves-effect btn brand" id="btn-user-edit-close">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>