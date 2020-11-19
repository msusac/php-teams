import { readProject } from './read.js';
import { searchProjectsTable } from './search.js';

$(document).ready(function () {

    //Clear form on button click
    $('#project-edit-clear-btn').on('click', function () {

        //Serialize form data
        var id = $("#modal-project-edit #projectHiddenId").val();

        //Clear validation messages
        clearMessages();

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/project/edit/get.php",
            data: {
                id: id
            },
            dataType: "json",
            encode: true,
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Update form field
                    $("#modal-project-edit #projectHiddenId").remove();
                    $('#form-project-edit div').after(data.content);
                    $('#form-project-edit :input[name="name"]').val(data.name);
                    $('#form-project-edit #description').val(data.description);
                }
            })
            //Fail promise callback
            .fail(function (data) {
                //Server failed to respond - show error message
                console.log(data);
                M.toast({ html: 'Could not reach server, please try again later.', classes: 'red rounded' });
            });
    });

    //Clear image field
    $("#project-edit-clear-image-btn").on('click', function (){
        $('#form-project-edit #image').val(null);
        $('#form-project-edit .file-path').val('');
    });

    //Call the action on form submit
    $('#form-project-edit').on('submit', function (event) {

        //Prevent form from refreshing page
        event.preventDefault();

        //Clear validation messages
        clearMessages();

        //Serialize form data
        var formData = new FormData(this);

        //Process form
        $.ajax({
            type: "POST",
            url: "/php-teams/project/edit/process.php",
            processData: false,
            contentType: false,
            cache: false,
            processData: false,
            data: formData,
            dataType: "json",
        })
            //Done promise callback
            .done(function (data) {
                if (data.success) {
                    //Show toast message
                    M.toast({ html: data.message, classes: 'green rounded' });

                    //Refresh projects table if only it is present
                    if ($("#table-projects").length) {
                        var formData = $('#form-projects-search').serialize();
                        searchProjectsTable(formData);

                        //Get hidden project id
                        var id = $("#modal-project-edit #projectHiddenId").val();

                        readProject(id);
                    }
                }
                //Show validation errors
                else {
                    //Show name error message
                    if (data.errors.name) {
                        $('#form-project-edit :input[name="name"]').after('<div class="red-text" id="text-error">' + data.errors.name + '</div>');
                    }
                    //Show description error message
                    if (data.errors.description) {
                        $('#form-project-edit #description').after('<div class="red-text" id="text-error">' + data.errors.description + '</div>');
                    }
                    //Show image error message
                    if (data.errors.image) {
                        M.toast({ html: data.errors.image, classes: 'red rounded' });
                    }
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
    });

    //Clear messages
    function clearMessages() {
        $('#text-error').remove();
        $('#text-sucess').remove();
        $('.green-text').remove();
        $('.red-text').remove();
    }
});

//Open edit project modal
function editProject() {

    //Get hidden project id
    var id = $("#modal-project-read #projectHiddenId").val();

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/project/edit/get.php",
        data: {
            id: id
        },
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Update field(s)
                $("#modal-project-edit #projectHiddenId").remove();
                $('#form-project-edit div').after(data.content);
                $('#form-project-edit :input[name="name"]').val(data.name);
                $('#form-project-edit #description').val(data.description);

                //Open modal
                $('#modal-project-edit').modal('open');
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
}

window.editProject = editProject;