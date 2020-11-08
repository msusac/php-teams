$(document).ready(function () {

    //Clear form on button click
    $('#register-clear-btn').click(function () {

        //Clear validation messages
        clearMessages();

        //Clear fields
        clearFields();
    });

    //Call the action on form submit
    $('#form-user-register').submit(function (event) {

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = $(this).serialize();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/user/register/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                //Show validation errors
                if (!data.success) {
                    if (data.errors.username) {
                        $('#form-user-register :input[name="username"]').after('<div class="red-text" id="text-error">' + data.errors.username + '</div>');
                    }
                    if (data.errors.email) {
                        $('#form-user-register :input[name="email"]').after('<div class="red-text" id="text-error">' + data.errors.email + '</div>');
                    }

                    if (data.errors.password) {
                        $('#form-user-register :input[name="password"]').after('<div class="red-text" id="text-error">' + data.errors.password + '</div>');
                    }

                    if (data.errors.sql) {
                        M.toast({ html: data.errors.sql, classes: 'red rounded' });
                    }
                }
                else {
                    //Clear fields
                    clearFields();

                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
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

    //Clear fields
    function clearFields() {
        $('#form-user-register :input[name="username"]').val('');
        $('#form-user-register :input[name="email"]').val('');
        $('#form-user-register :input[name="password"]').val('');
        $('#form-user-register :input[name="password_repeat"]').val('');
    }
});