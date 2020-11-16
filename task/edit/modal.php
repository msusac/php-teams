<div id="modal-task-edit" class="modal">
    <div class="modal-content">
        <h4>Edit Project Task details</h4>
        <div class="row">
            <form class="col s12" method="POST" action="process.php" id="form-task-edit">
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="name">Project Task name</label>
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
                    <div class="input-field col s8">
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
                    <div class="col s4">
                        <p id="project-label"></p>
                    </div>
                </div>
                <div class="row modal-form-row">
                    <div class="input-field col s8">
                        <select name="status" id="status">
                            <option value="" selected>Status</option>
                            <option value="NOT_STARTED">Not Started</option>
                            <option value="IN_PROGRESS">In Progress</option>
                            <option value="DONE">Done</option>
                            <option value="REVERSED">Reversed</option>
                        </select>
                    </div>
                    <div class="col s4">
                        <p id="status-label"></p>
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Save</button>
                    <a class="modal-action waves-effect btn brand" id="task-edit-clear-btn">Clear</a>
                    <a class="modal-action modal-close waves-effect btn brand">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>