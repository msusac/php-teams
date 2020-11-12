//Function for showing user details
export function readUser(username) {

    console.log(username);
    
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
                if(!$('#modal-user-read').hasClass('open')){
                    $('#modal-user-read').modal('open');
                }
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
};

window.readUser = readUser;