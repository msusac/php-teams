$(document).ready(function () {

    //Call the action on form submit
    $('#form-user-logout').submit(function (event) {

        //Serialize form data
        var formData = '';

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/user/logout/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    M.toast({ html: data.message, classes: 'green rounded' });
                    location.reload();
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
});