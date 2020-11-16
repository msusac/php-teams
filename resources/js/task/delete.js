import { searchTasksTable } from './search.js';

//Delete task
function deleteTask() {

    //Alert dialog
    if (confirm("Do you want to delete this project task?")) {
        //Serialize form data
        var id = $("#modal-task-read #taskHiddenId").val();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/task/delete/process.php",
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

                    //Close task read modal
                    $('#modal-task-read').modal('close');

                    //Update table
                    var formData = $('#form-tasks-search').serialize();
                    searchTasksTable(formData);
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

window.deleteTask = deleteTask;
