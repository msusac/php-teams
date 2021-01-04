// Fields
const formLogout = $('#form-user-logout');

// Logout Function
const logout = () => {

    // Process form
    $.ajax({
        type: "GET",
        url: "/php-teams/api/user/logout.php",
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Show success message
                M.toast({ html: data.message, classes: 'green rounded' });

                // Refresh page
                location.reload();
            }

            // Process Failed - Show error messages
            else {

                // Show session error message
                if (data.errors.session)
                    M.toast({ html: data.errors.session, classes: 'red rounded' });
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

    // Call the action on form submit
    formLogout.on('submit', function (event) {

        // Prevent form from refreshing page
        event.preventDefault();
        
        // Logout
        logout();
    });
});