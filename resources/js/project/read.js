//Function for showing project details
export function readProject(id) {

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/project/read/process.php",
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
                $('#modal-project-read .modal-content').html(data.content);

                //Open modal
                if(!$('#modal-project-read').hasClass('open')){
                    $('#modal-project-read').modal('open');
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

window.readProject = readProject;