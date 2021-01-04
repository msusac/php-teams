// Fields
const btnRegisterClear = $('#btn-register-clear');
const btnRegisterClose = $('#btn-register-close');

const formRegister = $('#form-user-register');

const registerInputUsername = $('#form-user-register :input[name="username"]');
const registerInputEmail = $('#form-user-register :input[name="email"]');
const registerInputPassword = $('#form-user-register :input[name="password"]');
const registerInputPasswordConfirm = $('#form-user-register :input[name="password_confirm"]');

// Clear messages function
const clearRegisterMessages = () => {

    $('#text-error').remove();
    $('#text-success').remove();

    $('.green-text').remove();
    $('.red-text').remove();
}

// Clear form fields function
const clearRegisterFields = () => {

    registerInputUsername.val('');
    registerInputEmail.val('');
    registerInputPassword.val('');
    registerInputPasswordConfirm.val('');
}

// Register function
const register = () => {

    // Serialize form data
    var formData = $(this).serialize();

    // Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/api/user/register.php",
        data: formData,
        dataType: "json",
        encode: true,
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Clear form fields
                clearRegisterFields();

                // Show success message
                M.toast({ html: data.message, classes: 'green rounded' });
            }

            // Process Failed - Show error messages
            else {

                // Show username error message
                if (data.errors.username)
                    registerInputUsername.after('<div class="red-text" id="text-error">' + data.errors.username + '</div>');

                // Show email error message
                if (data.errors.email)
                    registerInputEmail.after('<div class="red-text" id="text-error">' + data.errors.email + '</div>');

                // Show password error message
                if (data.errors.password)
                    registerInputPassword.after('<div class="red-text" id="text-error">' + data.errors.password + '</div>');

                // Show password confirm error message
                if (data.errors.password_confirm)
                    registerInputPasswordConfirm.after('<div class="red-text" id="text-error">' + data.errors.password_confirm + '</div>');

                // Show general error message
                if (data.errors.general)
                    M.toast({ html: data.errors.general, classes: 'red rounded' });

                // Show session error message
                if (data.errors.session)
                    M.toast({ html: data.errors.session, classes: 'red rounded' });

                // Show sql error message
                if (data.errors.sql)
                    M.toast({ html: data.errors.sql, classes: 'red rounded' });
            }
        })

        // Fail promise callback
        .fail(function (data) {

            // Server failed to respond - show error message
            console.error('Server error');
            console.error(data);
            M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
        });
}

$(function () {

    // Clear form on button click
    btnRegisterClear.on('click', function () {

        // Clear fields
        clearRegisterFields();

        // Clear form fields
        clearRegisterMessages();
    });

    // Clear fields and messages upong closing modal
    btnRegisterClose.on('click', function () {

        // Clear form fields
        clearRegisterFields();

        // Clear messages
        clearRegisterMessages();
    });

    // Call the action on form submit
    formRegister.on('submit', function (event) {

        // Prevent form from refreshing page
        event.preventDefault();

        // Clear validation messages
        clearRegisterMessages();
        
        register();
    });
});