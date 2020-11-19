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
                <div class="row left-align">
                    <div class="input-field col s6">
                        <b>Starting/Ending Dates (Optional)</b>
                    </div>
                </div>
                <div class="row center-align">
                    <div class="input-field col s6">
                        <label for="date-start">Start Date</label>
                        <input type="text" class="datepicker" id="date-start" name="date-start">
                    </div>
                    <div class="input-field col s6">
                        <label for="time-start">Start Time</label>
                        <input type="text" class="timepicker" id="time-start" name="time-start">
                    </div>
                </div>
                <div class="row center-align">
                    <div class="input-field col s6">
                        <label for="date-end">End Date</label>
                        <input type="text" class="datepicker" id="date-end" name="date-end">
                    </div>
                    <div class="input-field col s6">
                        <label for="time-end">End Time</label>
                        <input type="text" class="timepicker" id="time-end" name="time-end">
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Save</button>
                    <a class="modal-action waves-effect btn brand" id="task-edit-clear-date-btn">Clear Dates</a>
                    <a class="modal-action waves-effect btn brand" id="task-edit-clear-btn">Clear</a>
                    <a class="modal-action modal-close waves-effect btn brand">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>