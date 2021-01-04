// Fields
const btnLoginClose = $('#btn-login-close');

const formLogin = $('#form-user-login');

const loginInputUsername = $('#form-user-login :input[name="username"]');
const loginInputPassword = $('#form-user-login :input[name="password"]');

var textError = $('#text-error');
var textSuccess = $('#text-success');

var textGreen = $('.green-text');
var textRed = $('.red-text');

// Clear form fields function
const clearLoginFields = () => {

    loginInputUsername.val('');
    loginInputPassword.val('');
}

// Clear messages function
const clearLoginMessages = () => {

    $('#text-error').remove();
    $('#text-success').remove();

    $('.green-text').remove();
    $('.red-text').remove();
}

// Login Function
const login = () => {

    // Serialize form data
    var formData = formLogin.serialize();

    // Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/api/user/login.php",
        data: formData,
        dataType: "json",
        encode: true,
    })

        // Done promise callback
        .done(function (data) {

            // Proccess Done
            if (data.success) {

                // Clear form fields
                clearLoginFields();

                // Show success message
                M.toast({ html: data.message, classes: 'green rounded' });

                // Refresh page
                location.reload();
            }

            // Process Failed - Show error messages
            else {

                // Show username error message
                if (data.errors.username)
                    loginInputUsername.after('<div class="red-text" id="text-error">' + data.errors.username + '</div>');

                // Show password error message
                if (data.errors.password)
                    loginInputPassword.after('<div class="red-text" id="text-error">' + data.errors.password + '</div>');

                // Show login error message
                if (data.errors.login)
                    M.toast({ html: data.errors.login, classes: 'red rounded' });

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

    // Clear fields and messages upong closing modal
    btnLoginClose.on('click', function () {

        // Clear form fields
        clearLoginFields();

        // Clear messages
        clearLoginMessages();
    });

    // Call the action on form submit
    formLogin.on('submit', function (event) {

        // Prevent form from refreshing page
        event.preventDefault();

        // Clear validation messages
        clearLoginMessages();

        // Login Function
        login();
    });
});

