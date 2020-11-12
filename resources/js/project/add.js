import { searchProjectsTable } from './search.js';

$(document).ready(function () {

    //Clear form on button click
    $('#project-add-clear-btn').on('click', function () {

        //Clear validation messages
        clearMessages();

        //Clear form fields
        clearFields();
    });

    //Call the action on form submit
    $('#form-project-add').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = new FormData(this);

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/project/add/process.php",
            processData: false,
            contentType: false,
            cache: false,
            processData:false,
            data: formData,
            dataType: "json",
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Clear fields
                    clearFields();

                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Refresh projects table if only it is present
                    if($("#table-projects").length){
                        var formData = $('#form-projects-search').serialize();
                        searchProjectsTable(formData);
                    }
                }
                //Show validation errors
                else {
                    //Show name error message
                    if (data.errors.name) {
                        $('#form-project-add :input[name="name"]').after('<div class="red-text" id="text-error">' + data.errors.name + '</div>');
                    }
                    //Show description error message
                    if (data.errors.description) {
                        $('#form-project-add #description').after('<div class="red-text" id="text-error">' + data.errors.description + '</div>');
                    }
                    //Show image error message
                    if (data.errors.image) {
                        M.toast({ html: data.errors.image, classes: 'red rounded' });
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
        $('#form-project-add :input[name="name"]').val('');
        $('#form-project-add #description').val('');
        $('#form-project-add #image').val(null);
        $('#form-project-add .file-path').val('');
    }
});