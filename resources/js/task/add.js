import { searchTasksTable } from './search.js';

$(document).ready(function () {

    //Clear form on button click
    $('#task-add-clear-btn').on('click', function () {

        //Clear validation messages
        clearMessages();

        //Clear form fields
        clearFields();
    });

    //Call the action on form submit
    $('#form-task-add').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = $(this).serialize();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/task/add/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Clear fields
                    clearFields();

                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Refresh tasks table if only it is present
                    if($("#table-tasks").length){
                        var formData = $('#form-tasks-search').serialize();
                        searchTasksTable(formData);
                    }
                }
                //Show validation errors
                else {
                    //Show name error message
                    if (data.errors.name) {
                        $('#form-task-add :input[name="name"]').after('<div class="red-text" id="text-error">' + data.errors.name + '</div>');
                    }
                    //Show description error message
                    if (data.errors.description) {
                        $('#form-task-add #description').after('<div class="red-text" id="text-error">' + data.errors.description + '</div>');
                    }
                    //Show project selection error message
                    if (data.errors.project) {
                        $('#form-task-add select').after('<div class="red-text" id="text-error">' + data.errors.project + '</div>');
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

        //Prevent form from refreshing page
        event.preventDefault();
    });

    //Clear messages
    function clearMessages() {
        $('#text-error').remove();
        $('#text-sucess').remove();
        $('.green-text').remove();
        $('.red-text').remove();
    }

    //Clear form fields
    function clearFields() {
        $('#form-task-add :input[name="name"]').val('');
        $('#form-task-add #description').val('');

        $('#form-task-add select').prop('selectedIndex', 0);
        $('#form-task-add select').formSelect();
    }
});