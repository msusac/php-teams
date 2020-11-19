<div id="modal-request-add" class="modal">
    <div class="modal-content">
        <h4>Send a new Request</h4>
        <div class="row">
            <form class="col s12" method="POST" action="process.php" id="form-request-add">
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="name">Request  name</label>
                        <input id="name" name="name" type="text" class="validate" required>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="materialize-textarea"></textarea>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <select name="project" id="project">
                            <option value="" selected>Select Project</option>
                            <?php
                            foreach ($projectSelect as $project) {
                                //Prepare option fields
                                echo '<option value="' . $project['id'] . '">' . $project['name'] . ' - ' . $project['id'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <select name="user" id="user">
                            <option value="" selected>Select User</option>
                            <?php
                            foreach ($userSelect as $user) {
                                //Prepare option fields
                                echo '<option value="' . $user['id'] . '">' . $user['name'] .' </option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Add</button>
                    <a class="modal-action waves-effect btn brand" id="request-add-clear-btn">Clear</a>
                    <a class="modal-action modal-close waves-effect btn brand">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>