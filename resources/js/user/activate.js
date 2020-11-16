import { readUser } from './read.js';
import { searchUsersTable } from './search.js';

//Function for activating user
function activateUser(username) {

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/user/activate/process.php",
        data: {
            username: username
        },
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show success message
                M.toast({ html: data.message, classes: 'green rounded' });

                //Refresh user search table and modal only if user search table exists
                if ($('#table-users').length) {

                    //Serialize form data
                    var formData = $('#form-users-search').serialize();

                    //Read User Modal
                    readUser(username);

                    //Search Users Table
                    searchUsersTable(formData);

                    //Count not activated users
                    countNotActivatedUsers();
                }
            }
            else {
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
}

window.activateUser = activateUser;