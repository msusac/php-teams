// Fields
const modalReadUser = $('#modal-user-read');
const modalReadUserBody = $('#modal-user-read tbody');

// Read User Details by id
const readUserById = (id) => {

    // Process form
    $.ajax({
        type: "GET",
        url: "/php-teams/api/user/read.php?id=" + id,
        dataType: "json",
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Show JSON data;
                modalReadUserBody.html(data.table);

                // Open modal
                if (!modalReadUser.hasClass('open'))
                    modalReadUser.modal('open');
            }

            // Process Failed - Show error messages
            else {

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
};

const _readUserById = readUserById;
export { _readUserById as readUserById };

window.readUserById = readUserById;