import { readUserById } from './read.js';
import { searchUserTable } from './search.js';

// Fields
var tableUser = $('#table-user tbody');

// Function for activating user by id
const activateUserById = (id) => {

    // Process form
    $.ajax({
        type: "GET",
        url: "/php-teams/api/user/activate.php?id=" + id,
        dataType: "json",
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Show success message
                M.toast({ html: data.message, classes: 'green rounded' });

                // Read user by username
                readUserById(id);

                // Refresh users table if it exists
                if (tableUser.length) {

                    // Refresh user table
                    searchUserTable();
                }
            }

            // Process Failed - Show error messages
            else {

                // Show general error message
                if (data.errors.general)
                    M.toast({ html: data.errors.general, classes: 'red rounded' });

                // Show session error message
                if (data.errors.session)
                    M.toast({ html: data.errors.general, classes: 'red rounded' });

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

window.activateUserById = activateUserById;