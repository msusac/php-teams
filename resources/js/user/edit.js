$(document).ready(function () {

    //Clear form on button click
    $('#edit-user-clear-btn').click(function () {

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
                    //Update field
                    $('#form-user-edit :input[name="fullname"]').val(data.fullname);
                }
            })
            //Fail promise callback
            .fail(function (data) {
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    });

    //Call the action on form submit
    $('#form-user-edit').submit(function (event) {

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
                //Show validation errors
                if (!data.success) {
                    if (data.errors.password_old) {
                        $('#form-user-edit :input[name="password_old"]').after('<div class="red-text" id="text-error">' + data.errors.password_old + '</div>');
                    }
                    if (data.errors.password_new) {
                        $('#form-user-edit :input[name="password_new"]').after('<div class="red-text" id="text-error">' + data.errors.password_new + '</div>');
                    }

                    if (data.errors.password_new_repeat) {
                        $('#form-user-edit :input[name="password_new_repeat]').after('<div class="red-text" id="text-error">' + data.errors.password_new_repeat + '</div>');
                    }
                }
                else {
                    //Clear form fields
                    clearFields();

                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });
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
        $('#form-user-edit :input[name="password_old"]').val('');
        $('#form-user-edit :input[name="password_new"]').val('');
        $('#form-user-edit :input[name="password_new_repeat"]').val('');
    }
});

//Open edit user modal
function openEditUserModal() {

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

                //Close modal
                $('#modal-user-read').modal('close');

                //Open modal
                $('#modal-user-edit').modal('open');
            }
        })
        //Fail promise callback
        .fail(function (data) {
            M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
        });
}
