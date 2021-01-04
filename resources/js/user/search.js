// Fields
const btnUserSearchClear = $('#btn-user-search-clear');
const formUserSearch = $('#form-user-search');

const userSearchInputUsername  = $('#form-user-search :input[name="username"]');
const userSearchInputEmail = $('#form-user-search :input[name="email"]');
const userSearchSelectAll = $('#form-user-search select');

var tableUser = $("#table-user tbody");

// Function to clear user search table
const clearSearchUser = () => {

    userSearchInputUsername .val('');
    userSearchInputEmail.val('');
    userSearchSelectAll.val('');
    userSearchSelectAll.formSelect();

    searchUser();
}

// Function for searching and displaying user table
const searchUser = () => {

    var formData = formUserSearch.serialize();

    $.ajax({
        type: "POST",
        url: "/php-teams/api/user/search.php",
        data: formData,
        dataType: "json",
        encode: true,
    })

        // Done promise callback
        .done(function (data) {

            // Process Done
            if (data.success) {

                // Show data table
                tableUser.html(data.table);
            }

            // Process Failed - Show Error Messages
            else {

                // Show general error message
                if (data.errors.general)
                    M.toast({ html: data.errors.general, classes: 'red rounded' });

                // Show session error message
                if (data.errors.session)
                    M.toast({ html: data.errors.general, classes: 'red rounded' });

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

    // Show default results upon loading page
    searchUser();

    // Clear form and reset table on button click
    btnUserSearchClear.on('click', function () {
        clearSearchUser();
    });

    // Call the action on form submit
    formUserSearch.on('submit', function (event) {

        // Call search function
        searchUser();

        // Prevent form from refreshing page
        event.preventDefault();
    });
});

const _searchUser = searchUser;
export { _searchUser as searchUser };