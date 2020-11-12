import { searchProjectsTable } from './search.js';

//Delete project
function deleteProject() {

    //Alert dialog
    if (confirm("Do you want to delete? This will delete all task and requests related to this project?")) {
        //Serialize form data
        var id = $("#modal-project-read #projectHiddenId").val();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/project/delete/process.php",
            data: {
                id: id
            },
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Show success message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Close project read modal
                    $('#modal-project-read').modal('close');

                    //Update table
                    var formData = $('#form-projects-search').serialize();
                    searchProjectsTable(formData);
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
                console.log(data);
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    }
}

window.deleteProject = deleteProject;
