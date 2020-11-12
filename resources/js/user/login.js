$(document).ready(function () {

    //Call the action on form submit
    $('#form-user-login').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = $(this).serialize();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/user/login/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Clear form fields
                    clearFields();

                    //Show success message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Refresh page
                    location.reload();
                }
                //Show validation errors
                else {
                    //Show username error message
                    if (data.errors.username) {
                        $('#form-user-login :input[name="username"]').after('<div class="red-text" id="text-error">' + data.errors.username + '</div>');
                    }
                    //Show password error message
                    if (data.errors.password) {
                        $('#form-user-login :input[name="password"]').after('<div class="red-text" id="text-error">' + data.errors.password + '</div>');
                    }
                    //Show login error message
                    if (data.errors.login) {
                        M.toast({ html: data.errors.login, classes: 'red rounded' });
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
        $('#form-user-login :input[name="username"]').val('');
        $('#form-user-login :input[name="password"]').val('');
    }
});