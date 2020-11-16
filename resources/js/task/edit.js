import { readTask } from './read.js';
import { searchTasksTable } from './search.js';

$(document).ready(function () {

    //Clear form on button click
    $('#task-edit-clear-btn').on('click', function () {

        //Serialize form data
        var id = $("#modal-task-edit #taskHiddenId").val();

        //Clear validation messages
        clearMessages();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/task/edit/get.php",
            data: {
                id: id
            },
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Update field(s)
                    $("#modal-task-edit #taskHiddenId").remove();
                    $('#form-task-edit div').after(data.content);

                    $('#form-task-edit :input[name="name"]').val(data.name);
                    $('#form-task-edit #description').val(data.description);
                    $('#form-task-edit #project-label').text('(' + data.project + ')');
                    $('#form-task-edit #status-label').text('(' + data.status + ')');

                    $('#form-task-edit select').prop("selectedIndex", 0);
                    $('#form-task-edit select').formSelect();
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
                console.log(data);
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    });

    //Call the action on form submit
    $('#form-task-edit').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = $(this).serialize();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/task/edit/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Refresh tasks table if only it is present
                    if ($("#table-tasks").length) {
                        var formData = $('#form-tasks-search').serialize();
                        searchTasksTable(formData);

                        //Get hidden task id
                        var id = $("#modal-task-edit #taskHiddenId").val();

                        //Read Task Modal
                        readTask(id);

                        //Update fields
                        $('#form-task-edit #project-label').text('(' + data.project + ')');
                        $('#form-task-edit #status-label').text('(' + data.status + ')');
        
                        $('#form-task-edit select').prop("selectedIndex", 0);
                        $('#form-task-edit select').formSelect();
                    }
                }
                //Show validation errors
                else {
                    //Show name error message
                    if (data.errors.name) {
                        $('#form-task-edit :input[name="name"]').after('<div class="red-text" id="text-error">' + data.errors.name + '</div>');
                    }
                    //Show description error message
                    if (data.errors.description) {
                        $('#form-task-edit #description').after('<div class="red-text" id="text-error">' + data.errors.description + '</div>');
                    }
                    //Show project selection error message
                    if (data.errors.project) {
                        $('#form-task-edit #project').after('<div class="red-text" id="text-error">' + data.errors.project + '</div>');
                    }
                    //Show project selection error message
                    if (data.errors.status) {
                        $('#form-task-edit #status').after('<div class="red-text" id="text-error">' + data.errors.status + '</div>');
                    }
                    //Show sql error message
                    if (data.errors.sql) {
                        M.toast({ html: data.errors.sql, classes: 'red rounded' });
                    }
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
                console.log(data);
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    });

    //Clear messages
    function clearMessages() {
        $('#text-error').remove();
        $('#text-sucess').remove();
        $('.green-text').remove();
        $('.red-text').remove();
    }
});

//Open edit task modal
function editTask() {

    //Get hidden task id
    var id = $("#modal-task-read #taskHiddenId").val();

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/task/edit/get.php",
        data: {
            id: id
        },
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Update field(s)
                $("#modal-task-edit #taskHiddenId").remove();
                $('#form-task-edit div').after(data.content);

                $('#form-task-edit :input[name="name"]').val(data.name);
                $('#form-task-edit #description').val(data.description);
                $('#form-task-edit #project-label').text('(' + data.project + ')');
                $('#form-task-edit #status-label').text('(' + data.status + ')');

                $('#form-task-edit select').prop("selectedIndex", 0);
                $('#form-task-edit select').formSelect();

                //Open modal
                $('#modal-task-edit').modal('open');
            }
            //Show validation errors
            else {
                //Show sql error message
                if (data.errors.sql) {
                    M.toast({ html: data.errors.sql, classes: 'red rounded' });
                }

                //Show session error message
                if (data.errors.session) {
                    M.toast({ html: data.errors.session, classes: 'red rounded' });
                }
            }
        })
        //Fail promise callback
        .fail(function (data) {
            console.log(data);
            M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
        });
}

window.editTask = editTask;