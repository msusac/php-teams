import { paginateTable } from '../other/header.js';

$(document).ready(function () {

    //Show default results upon loading page if project requests table is present
    if ($('#table-requests').length) {
        var formData = $('#form-requests-search').serialize();
        searchRequestsTable(formData);
    }

    //Call the action on form submit
    $('#form-requests-search').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Serialize form data
        var formData = $(this).serialize();

        //Call function
        searchRequestsTable(formData);
    });

    //Clear form and reset table on button click
    $('#requests-search-clear-btn').on('click', function () {
        $('#form-requests-search :input[name="name"]').val('');

        $('#form-requests-search select').prop('selectedIndex', 0);
        $('#form-requests-search select').formSelect();

        var formData = $('#form-requests-search').serialize();
        searchRequestsTable(formData);
    });
});

//Function for searching and displaying project requests table
export function searchRequestsTable(formData) {
    $.ajax({
        type: "POST",
        url: "/php-teams/request/search/process.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show data table
                $("#table-requests tbody").html(data.table);

                //Paginate tasks table
                paginateTable();
            }
             //Show validation errors
            else {
                //Show sql error message
                if (data.errors.sql) {
                    M.toast({ html: data.errors.sql, classes: 'red rounded' });
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

window.searchRequestsTable = searchRequestsTable;
