import { readUserById } from './read.js';

// Fields
const btnUserEditClose = $("#btn-user-edit-close");
const btnUserEditClear = $("#btn-user-edit-clear");

const userEditInputFullname = $('#form-user-edit :input[name="fullname"]');
const userEditInputPasswordOld = $('#form-user-edit :input[name="password_old"]');
const userEditInputPasswordNew = $('#form-user-edit :input[name="password_new"]');
const userEditInputPasswordNewConfirm = $('#form-user-edit :input[name="password_new_confirm"]');

const formUserEdit = $("#form-user-edit");

const modalUserEdit = $('#modal-user-edit');

// Clear messages function
const clearEditUserMessages = () => {

    $('#text-error').remove();
    $('#text-success').remove();

    $('.green-text').remove();
    $('.red-text').remove();
}

// Clear form fields function
const clearEditUserFields = () => {

    userEditInputPasswordOld.val('');
    userEditInputPasswordNew.val('');
    userEditInputPasswordNewConfirm.val('');
}

// Edit User function
const editUser = () => {

    var formData = formUserEdit.serialize();

    // Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/api/user/edit.php",
        data: formData,
        dataType: "json",
        encode: true,
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Clear messages
                clearEditUserMessages();

                // Clear form fields
                clearEditUserFields();

                // Show success message
                M.toast({ html: data.message, classes: 'green rounded' });

                //Update Read User Modal
                readUserById(data.user);
            }

            // Process Failed - Show error messages
            else {

                // Show old password error message
                if (data.errors.password_old)
                    userEditInputPasswordOld.after('<div class="red-text" id="text-error">' + data.errors.password_old + '</div>');

                // Show new password error message
                if (data.errors.password_new)
                    userEditInputPasswordNew.after('<div class="red-text" id="text-error">' + data.errors.password_new + '</div>');

                // Show confirm new password error message
                if (data.errors.password_new_confirm)
                    userEditInputPasswordNewConfirm.after('<div class="red-text" id="text-error">' + data.errors.password_new_confirm + '</div>');

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


// Open User Edit Modal
const openUserEdit = () => {

    // Process form
    $.ajax({
        type: "GET",
        url: "/php-teams/api/user/edit_get.php",
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Open Edit modal
                if (!modalUserEdit.hasClass('open'))
                    modalUserEdit.modal('open');

                // Update fullname field
                userEditInputFullname.val(data.fullname);
            }

            // Process Failed - Show error messsages
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
}

$(function () {

    // Clear form on button click
    btnUserEditClear.on('click', function () {

        // Clear form fields
        clearEditUserFields();

        // Retrieve data
        openUserEdit();
    });

    // Clear fields and messages upong closing modal
    btnUserEditClose.on('click', function () {

        // Clear messages
        clearEditUserMessages();

        // Clear form fields
        clearEditUserFields();

        // Remove fullname field
        userEditInputFullname.val('');
    });

    // Call the action on form submit
    formUserEdit.on('submit', function (event) {

        // Prevent form from refreshing page
        event.preventDefault();

        // Clear messages
        clearEditUserMessages();

        editUser();
    });
});

window.openUserEdit = openUserEdit;