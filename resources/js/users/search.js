$(document).ready(function () {

    //Show default results upon loading page
    var formData = $('#form-users-search').serialize();
    searchUsersTable(formData);

    //Call the action on form submit
    $('#form-users-search').submit(function (event) {

        //Serialize form data
        var formData = $(this).serialize();

        //Call function
        searchUsersTable(formData);

        //Prevent form from refreshing page
        event.preventDefault();
    });

    //Clear form and reset table on button click
    $('#users-search-clear-btn').click(function () {
        $('#form-users-search :input[name="username"]').val('');
        $('#form-users-search :input[name="email"]').val('');
        $('#form-users-search select').val('');

        var formData = $('#form-users-search').serialize();
        searchUsersTable(formData);
    });

    //Function for searching and displaying users table
    function searchUsersTable(formData) {
        $.ajax({
            type: "POST",
            url: "/php-teams/users/search/process.php",
            data: formData,
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                //Show validation errors
                if (!data.success) {
                    if (data.errors.sql) {
                        //Show sql error message
                        console.log(data.errors.sql);
                        M.toast({ html: data.errors.sql, classes: 'red rounded' });
                    }
                }
                else {
                    //Show data table
                    $("#table-users tbody").html(data.table);
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    }
});