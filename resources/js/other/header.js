$(document).ready(function () {

    //Initialize datepciker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        showClearBtn: true
    });

    //Initialize modal
    $('.modal').modal({
        dismissible: false
    });

    //Initialize select
    $('select').formSelect();

    //Initialize sidenav
    $('.sidenav').sidenav();

    //Initialize slider
    $('.slider').slider();

    //Initialize timepicker
    $('.timepicker').timepicker({
        showClearBtn: true,
        twelveHour: false
    });

    countNotActivatedUsers();
    countPendingRequests();
});

//Function for counting not activated users
export function countNotActivatedUsers(){

    //Serialize form data
    var formData = '';

    $.ajax({
        type: "POST",
        url: "/php-teams/user/count/process.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show JSON data;
                if(data.count){
                    $('[id="user-not-activated-count"]').text('(' + data.count + ')');
                }
                else{
                    $('[id="user-not-activated-count"]').text('');
                }
            }
        });
}

//Function for counting pending requests
export function countPendingRequests(){

    //Serialize form data
    var formData = '';

    $.ajax({
        type: "POST",
        url: "/php-teams/request/count/process.php",
        data: formData,
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show JSON data;
                if(data.count){
                    $('[id="request-pending-count"]').text('(' + data.count + ')');
                }
                else{
                    $('[id="request-pending-count"]').text('');
                }
            }
        });
}

//Function to paginate table
export function paginateTable(){
    $('.pager').empty();

    $('table').pageMe({
        pagerSelector: '.pager',
        activeColor: 'blue',
        prevText: 'Anterior',
        nextText: 'Siguiente',
        showPrevNext: true,
        hidePageNumbers: false,
        perPage: 5
    });
}

window.countNotActivatedUsers = countNotActivatedUsers;
window.countPendingRequests = countPendingRequests;
window.paginateTable = paginateTable;