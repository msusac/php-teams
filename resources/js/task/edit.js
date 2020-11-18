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
                    $('#form-task-edit #status-label').text('(' + data.status + ')');

                    if(data.dateStart){
                        $('#form-task-edit input[name="date-start"]').val(data.dateStart);
                    }
                    else{
                        $('#form-task-edit input[name="date-start"]').val('');
                    }
                    if(data.dateEnd){
                        $('#form-task-edit input[name="date-end"]').val(data.dateEnd);
                    }
                    else{
                        $('#form-task-edit input[name="date-end"]').val('');
                    }
                    if(data.timeStart){
                        $('#form-task-edit input[name="time-start"]').val(data.timeStart);
                    }
                    else{
                        $('#form-task-edit input[name="time-start"]').val('');
                    }
                    if(data.timeEnd){
                        $('#form-task-edit input[name="time-end"]').val(data.timeEnd);
                    }
                    else{
                        $('#form-task-edit input[name="time-end"]').val('');
                    }

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

    //Clear date fields
    $('#task-edit-clear-date-btn').on('click', function () {
        $('#form-task-edit .datepicker').val('');
        $('#form-task-edit .timepicker').val('');
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
                    //Show status selection error message
                    if (data.errors.status) {
                        $('#form-task-edit #status').after('<div class="red-text" id="text-error">' + data.errors.status + '</div>');
                    }
                    //Show starting date error message
                    if (data.errors.dateStart) {
                        $('#form-task-edit #date-start').after('<div class="red-text" id="text-error">' + data.errors.dateStart + '</div>');
                    }
                    //Show ending date error message
                    if (data.errors.dateEnd) {
                        $('#form-task-edit #date-end').after('<div class="red-text" id="text-error">' + data.errors.dateEnd + '</div>');
                    }
                    //Show starting time error message
                    if (data.errors.timeStart) {
                        $('#form-task-edit #time-start').after('<div class="red-text" id="text-error">' + data.errors.timeStart + '</div>');
                    }
                    //Show ending time error message
                    if (data.errors.timeEnd) {
                        $('#form-task-edit #time-end').after('<div class="red-text" id="text-error">' + data.errors.timeEnd + '</div>');
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
                $('#form-task-edit #status-label').text('(' + data.status + ')');

                $('#form-task-edit select').prop("selectedIndex", 0);
                $('#form-task-edit select').formSelect();

                if(data.dateStart){
                    $('#form-task-edit input[name="date-start"]').val(data.dateStart);
                }
                else{
                    $('#form-task-edit input[name="date-start"]').val('');
                }
                if(data.dateEnd){
                    $('#form-task-edit input[name="date-end"]').val(data.dateEnd);
                }
                else{
                    $('#form-task-edit input[name="date-end"]').val('');
                }
                if(data.timeStart){
                    $('#form-task-edit input[name="time-start"]').val(data.timeStart);
                }
                else{
                    $('#form-task-edit input[name="time-start"]').val('');
                }
                if(data.timeEnd){
                    $('#form-task-edit input[name="time-end"]').val(data.timeEnd);
                }
                else{
                    $('#form-task-edit input[name="time-end"]').val('');
                }

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