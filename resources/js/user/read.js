//Function for showing user details
function readUser(username) {

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/user/read/process.php",
        data: {
            username: username
        },
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show JSON data;
                $('#modal-user-read tbody').html(data.table);

                //Open modal
                $('#modal-user-read').modal('close');
                $('#modal-user-read').modal('open');
            }
            else {
                if (data.errors.sql) {
                    //Show sql error message
                    M.toast({ html: data.errors.sql, classes: 'red rounded' });
                }
            }
        })
        //Fail promise callback
        .fail(function (data) {
            M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
        });
};