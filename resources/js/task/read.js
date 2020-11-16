//Function for showing task details
export function readTask(id) {

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/task/read/process.php",
        data: {
            id: id
        },
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show JSON data;
                $('#modal-task-read .modal-content').html(data.content);

                //Open modal
                if(!$('#modal-task-read').hasClass('open')){
                    $('#modal-task-read').modal('open');
                }
            }
            //Show validation errors
            else {
                //Show sql error message
                if (data.errors.sql) {
                    M.toast({ html: data.errors.sql, classes: 'red rounded' });
                }
                //Show session error message
                if (data.errors.session) {
                    M.toast({ html: data.errors.session, classes: 'red rounded' });
                }
            }
        })
        //Fail promise callback
        .fail(function (data) {
            console.log(data);
            M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
        });
};

window.readTask = readTask;