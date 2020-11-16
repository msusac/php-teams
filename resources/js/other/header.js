$(document).ready(function () {

    countNotActivatedUsers();
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
                $('[id="user-not-activated-count"]').text('(' + data.count + ')');
            }
        })
}

window.countNotActivatedUsers = countNotActivatedUsers;