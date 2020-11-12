<div id="modal-project-add" class="modal">
    <div class="modal-content">
        <h4>Create a new project</h4>
        <div class="row">
            <form class="col s12" method="POST" action="process.php" id="form-project-add" enctype="multipart/form-data">
                <div class="row modal-form-row">
                    <div class="input-field col s12">
                        <label for="name">Project name</label>
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
                    <div class="file-field input-field col s12">
                        <div class="btn">
                            <span>Image (Optional)</span>
                            <input type="hidden" name="size" value="1000000">
                            <input id="image" name="image" type="file" accept='image/*'>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
                <div class="row center-align">
                    <button type="submit" name="submit" value="submit" class="modal-action waves-effect btn brand">Add</button>
                    <a class="modal-action waves-effect btn brand" id="project-add-clear-btn">Clear</a>
                    <a class="modal-action modal-close waves-effect btn brand">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>