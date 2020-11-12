$(document).ready(function () {

    //Show default results upon loading page if projects table is present
    if ($('#table-projects').length) {
        var formData = $('#form-projects-search').serialize();
        searchProjectsTable(formData);
    }

    //Call the action on form submit
    $('#form-projects-search').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Serialize form data
        var formData = $(this).serialize();

        //Call function
        searchProjectsTable(formData);
    });

    //Clear form and reset table on button click
    $('#projects-search-clear-btn').click(function () {
        $('#form-projects-search :input[name="name"]').val('');
        $('#form-projects-search :input[name="user"]').val('');
        $('#form-projects-search select').val('');

        var formData = $('#form-projects-search').serialize();
        searchProjectsTable(formData);
    });
});

//Function for searching and displaying projects table
export function searchProjectsTable(formData) {
    $.ajax({
        type: "POST",
        url: "/php-teams/project/search/process.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show data table
                $("#table-projects tbody").html(data.table);
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

window.searchProjectsTable = searchProjectsTable;