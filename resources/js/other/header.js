$(document).ready(function () {

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

window.countNotActivatedUsers = countNotActivatedUsers;
window.countPendingRequests = countPendingRequests;