import { readRequest } from './read.js';
import { searchRequestsTable } from './search.js';

//Function to accept request
function acceptRequest() {
    replyRequest('ACCEPTED');
}

//Function to reject request
function rejectRequest() {
    replyRequest('REJECTED');
}

function replyRequest(status) {

    //Get hidden task id
    var id = $("#modal-request-read #requestHiddenId").val();

    console.log(id);

    //Process form
    $.ajax({
        type: "POST",
        url: "/php-teams/request/reply/process.php",
        data: {
            id: id,
            status: status
        },
        dataType: "json",
        encode: true,
    })
        //Done promise callback
        .done(function (data) {
            if (data.success) {
                //Show toast message
                M.toast({ html: data.message, classes: 'green rounded' });

                //Count all pending requests
                countPendingRequests();

                //Refresh requests table if only it is present
                if ($("#table-requests").length) {
                    var formData = $('#form-requests-search').serialize();
                    searchRequestsTable(formData);
                }

                //Get hidden request id
                var id = $("#modal-request-read #requestHiddenId").val();

                //Read Request Modal
                readRequest(id);
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

window.acceptRequest = acceptRequest;
window.rejectRequest = rejectRequest;
window.replyRequest = rejectRequest;