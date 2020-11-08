$(document).ready(function () {

    //Call the action on form submit
    $('#form-user-login').submit(function (event) {

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
                //Show validation errors
                if (!data.success) {
                    if (data.errors.username) {
                        $('#form-user-login :input[name="username"]').after('<div class="red-text" id="text-error">' + data.errors.username + '</div>');
                    }

                    if (data.errors.password) {
                        $('#form-user-login :input[name="password"]').after('<div class="red-text" id="text-error">' + data.errors.password + '</div>');
                    }

                    if (data.errors.login) {
                        M.toast({ html: data.errors.login, classes: 'red rounded' });
                    }

                    if (data.errors.sql) {
                        //Show sql error message
                        M.toast({ html: data.errors.sql, classes: 'red rounded' });
                    }
                }
                else {
                    //Clear form fields
                    clearFields();

                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Refresh page
                    location.reload();
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
        $('#form-user-login :input[name="username"]').val('');
        $('#form-user-login :input[name="password"]').val('');
    }
});