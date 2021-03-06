import { readUser } from './read.js';

$(document).ready(function () {

    //Clear form on button click
    $('#user-edit-clear-btn').on('click', function () {

        //Clear validation messages
        clearMessages();

        //Clear fields
        clearFields();

        //Serialize form data
        var formData = '';

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/user/edit/get.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Update form field
                    $('#form-user-edit :input[name="fullname"]').val(data.fullname);
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
                console.log(data);
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    });

    //Call the action on form submit
    $('#form-user-edit').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = $(this).serialize();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/user/edit/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Clear form fields
                    clearFields();

                    //Update User Read Modal
                    readUser(data.user);

                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });
                }
                //Show validation errors
                else {
                    //Show old password error message
                    if (data.errors.password_old) {
                        $('#form-user-edit :input[name="password_old"]').after('<div class="red-text" id="text-error">' + data.errors.password_old + '</div>');
                    }
                    //Show new password error message
                    if (data.errors.password_new) {
                        $('#form-user-edit :input[name="password_new"]').after('<div class="red-text" id="text-error">' + data.errors.password_new + '</div>');
                    }
                    //Show repeat new password error message
                    if (data.errors.password_new_repeat) {
                        $('#form-user-edit :input[name="password_new_repeat]').after('<div class="red-text" id="text-error">' + data.errors.password_new_repeat + '</div>');
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
        $('#form-user-edit :input[name="password_old"]').val('');
        $('#form-user-edit :input[name="password_new"]').val('');
        $('#form-user-edit :input[name="password_new_repeat"]').val('');
    }
});

//Open edit user modal
function editUser() {

    //Serialize form data
    var formData = '';

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/user/edit/get.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Update field
                $('#form-user-edit :input[name="fullname"]').val(data.fullname);

                //Open modal
                $('#modal-user-edit').modal('open');
            }
            //Show validation errors
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
            console.log(data);
            M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
        });
}

window.editUser = editUser;
