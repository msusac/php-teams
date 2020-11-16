$(document).ready(function () {

    //Show default results upon loading page if users table is present
    if ($('#table-users').length) {
        var formData = $('#form-users-search').serialize();
        searchUsersTable(formData);
    }

    //Call the action on form submit
    $('#form-users-search').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Serialize form data
        var formData = $(this).serialize();

        //Call function
        searchUsersTable(formData);
    });

    //Clear form and reset table on button click
    $('#users-search-clear-btn').on('click', function () {
        $('#form-users-search :input[name="username"]').val('');
        $('#form-users-search :input[name="email"]').val('');
        $("#form-users-search select").prop("selectedIndex", 0);
        $("#form-users-search select").formSelect();

        var formData = $('#form-users-search').serialize();
        searchUsersTable(formData);
    });
});

//Function for searching and displaying users table
export function searchUsersTable(formData) {
    $.ajax({
        type: "POST",
        url: "/php-teams/user/search/process.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            //Show validation errors
            if (data.success) {
                //Show data table
                $("#table-users tbody").html(data.table);
            }
            //Show validation errors
            else {
                if (data.errors.sql) {
                    //Show sql error message
                    M.toast({ html: data.errors.sql, classes: 'red rounded' });
                }
                if (data.errors.session) {
                    //Show session error message
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

window.searchUsersTable = searchUsersTable;