import { paginateTable } from '../other/header.js';

$(document).ready(function () {

    //Show default results upon loading page if project tasks table is present
    if ($('#table-tasks').length) {
        var formData = $('#form-tasks-search').serialize();
        searchTasksTable(formData);
    }

    //Call the action on form submit
    $('#form-tasks-search').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Serialize form data
        var formData = $(this).serialize();

        //Call function
        searchTasksTable(formData);
    });

    //Clear form and reset table on button click
    $('#tasks-search-clear-btn').on('click', function () {
        $('#form-tasks-search :input[name="name"]').val('');

        $('#form-tasks-search select').prop('selectedIndex', 0);
        $('#form-tasks-search select').formSelect();

        var formData = $('#form-tasks-search').serialize();
        searchTasksTable(formData);
    });
});

//Function for searching and displaying project tasks table
export function searchTasksTable(formData) {
    $.ajax({
        type: "POST",
        url: "/php-teams/task/search/process.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show data table
                $("#table-tasks tbody").html(data.table);

                //Paginate Tasks table
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

window.searchTasksTable = searchTasksTable;
