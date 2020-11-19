import { searchRequestsTable } from './search.js';

$(document).ready(function () {

    //Clear form on button click
    $('#request-add-clear-btn').on('click', function () {

        //Clear validation messages
        clearMessages();

        //Clear form fields
        clearFields();
    });

    //Call the action on form submit
    $('#form-request-add').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = $(this).serialize();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/request/add/process.php",
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

                    //Refresh requests table if only it is present
                    if($("#table-requests").length){
                        var formData = $('#form-requests-search').serialize();
                        searchRequestsTable(formData);
                    }
                }
                //Show validation errors
                else {
                    //Show name error message
                    if (data.errors.name) {
                        $('#form-request-add :input[name="name"]').after('<div class="red-text" id="text-error">' + data.errors.name + '</div>');
                    }
                    //Show description error message
                    if (data.errors.description) {
                        $('#form-request-add #description').after('<div class="red-text" id="text-error">' + data.errors.description + '</div>');
                    }
                    //Show project selection error message
                    if (data.errors.project) {
                        $('#form-request-add #project').after('<div class="red-text" id="text-error">' + data.errors.project + '</div>');
                    }
                    //Show user selection error message
                    if (data.errors.user) {
                        $('#form-request-add #user').after('<div class="red-text" id="text-error">' + data.errors.user + '</div>');
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
        $('#form-request-add :input[name="name"]').val('');
        $('#form-request-add #description').val('');

        $('#form-request-add select').prop('selectedIndex', 0);
        $('#form-request-add select').formSelect();
    }
});