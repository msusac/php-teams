$(document).ready(function () {

    //Clear form on button click
    $('#register-clear-btn').on('click', function () {

        //Clear validation messages
        clearMessages();

        //Clear fields
        clearFields();
    });

    //Call the action on form submit
    $('#form-user-register').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

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
                if (data.success) {
                   //Clear form fields
                   clearFields();

                   //Show toast message
                   M.toast({ html: data.message, classes: 'green rounded' });
                }
                //Show validation errors
                else {
                    //Show username error message
                    if (data.errors.username) {
                        $('#form-user-register :input[name="username"]').after('<div class="red-text" id="text-error">' + data.errors.username + '</div>');
                    }
                    //Show email error message
                    if (data.errors.email) {
                        $('#form-user-register :input[name="email"]').after('<div class="red-text" id="text-error">' + data.errors.email + '</div>');
                    }
                    //Show password error message
                    if (data.errors.password) {
                        $('#form-user-register :input[name="password"]').after('<div class="red-text" id="text-error">' + data.errors.password + '</div>');
                    }
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

    //Clear form fields
    function clearFields() {
        $('#form-user-register :input[name="username"]').val('');
        $('#form-user-register :input[name="email"]').val('');
        $('#form-user-register :input[name="password"]').val('');
        $('#form-user-register :input[name="password_repeat"]').val('');
    }
});