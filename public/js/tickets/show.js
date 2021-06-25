// FOR ASSIGNING EQUIPMENT WITH PARTICULAR SERIAL NUMBERS 
function availableSerialNums(equipment_div, serial_nums_div) {

    let equipment_id = $("#" + equipment_div).val();
    if(equipment_id == ''){
        $("#" + serial_nums_div).html('');
        return;
    }

    $.ajax({
       'url' : '/equipment-serial-numbers/' + equipment_id,
       'type' : 'GET',
       'success': (response) => {
        let serial_numbers = response;
        let options = '';
        serial_numbers.forEach((number) => {
            // let selected = '';
            // if(equipment_id && equipment_id == number.equipment_id) selected = 'selected';
            options += `<option value=\"${number.id}\">${number.serial_number}</option>`;
        });
        $("#" + serial_nums_div).html(options);
       }
    });
}

// TAKE OVER BUTTON
$('#take_over_button').on('click', function() {
    swal({
        title: 'Are you sure?',
        icon: 'warning',
        buttons: {
            cancel: {
            text: "Cancel",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "OK",
            value: true,
            visible: true,
            className: "",
            closeModal: true
            }},
        }).then((value) => {
            if (value) {
                $('#update1-form').submit();
        }
    });
});

//  EDIT USER DECISION MODALS
function showAndHide(divToShow, divToHide, close=false) {
        
    if (close == false) {
        divToHide.addClass('d-none')
        divToHide.removeClass('d-block')
    
        divToShow.addClass('d-block')
        divToShow.removeClass('d-none')
    } else if (close == true) {
        divToHide.addClass('d-none')
        divToHide.removeClass('d-block')

        divToShow.addClass('d-none')
        divToShow.removeClass('d-block')
    }
}
    
let select = $('#ticket_request_type_id')
let supplies_div = $('#supplies_div')
let equipment_div =  $('#equipment_div')

// unlike for creating a new request, in this case I don't empty the input fields when they change the type of request, 
// so that the user would be able to change their mind multiple times and still retain the original data
// instead, in the controller, I check the type of request and based on that pass on the pertinent data
    select.on('change', () => {
       if (select.val() == '1') {
           showAndHide(equipment_div, supplies_div)
       } else if (select.val() == '2') {
          showAndHide(supplies_div, equipment_div)
       } else if (select.val() == '') {
            showAndHide(equipment_div, supplies_div, true)
       }
    });
    
// for editing user ticket - based on the already selected request type, display the right fields
function showData(ticket_request_type_id) {
    select.val(ticket_request_type_id).change();
}

// for editing officer response 

// grab the divs which we will either show or hide
let equipment_div_officer_edit = $('#equipment-div-officer-edit');
let details_div_officer_edit = $('#details-div-officer-edit');

function officerEditDisplay(ticket_type, ticket_request_type, flag=false, officer_approval = null) {

        /** TICKET TYPES **/
        // const NEW_EQUIPMENT = 1;
        // const REPAIR_EQUIPMENT = 2;
    
        // /** TICKET REQUEST TYPES **/
        // const EQUIPMENT_REQUEST = 1;
        // const OFFICE_SUPPLIES_REQUEST = 2;


    if (flag == true) {
        officer_approval = $('#officer_approval_select').val();
    }
    //new equipment request
    if (ticket_type == 1) {
        // if approved
        if (officer_approval == 2) {
            equipment_div_officer_edit.removeClass('d-none');
            details_div_officer_edit.removeClass('d-none');
            // if rejected
        } else if (officer_approval == 3) {
            equipment_div_officer_edit.addClass('d-none');
            details_div_officer_edit.addClass('d-none');
        }
        // repair equipment request
    }  else if (ticket_type == 2) {
        // if approved
        if (officer_approval == 2) {
            equipment_div_officer_edit.addClass('d-none');
            details_div_officer_edit.removeClass('d-none');
            // if rejected
        } else if (officer_approval == 3) {
            equipment_div_officer_edit.addClass('d-none');
            details_div_officer_edit.addClass('d-none');
        }
        //  office supplies request     
    } else if (ticket_request_type == 2) {
        // if approved
        if (officer_approval == 2) {
            equipment_div_officer_edit.addClass('d-none');
            details_div_officer_edit.removeClass('d-none');
            // if rejected
        } else if (officer_approval == 3) {
            equipment_div_officer_edit.addClass('d-none');
            details_div_officer_edit.addClass('d-none');
        }
    }
}

$('.confirm-delete-btn').on('click', function(e) {
    // e.stopPropagation();
    // e.preventDefault();
    // getting the id from the button that caused the event to fire
    let id = $(this).attr('data-id');
    swal({
        title: 'Are you sure?',
        text: "All information about this request will be deleted.",
        icon: 'warning',
        dangerMode: true,
        buttons: {
            cancel: {
            text: "Cancel",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "OK",
            value: true,
            visible: true,
            className: "",
            closeModal: true
            }},
        }).then((value) => {
            if (value) {
            $("#delete_form_"+ id).submit();
        }
    });
});

function createErrorMessage(error_text) {
    let error_message = document.createElement('p');
    error_message.innerHTML = error_text;
    error_message.setAttribute("style", "color: red;");
    error_message.classList.add('invalid-feedback');
    return error_message;
}
function handleErrorsOfficer(err_array, deadline, price) {
    var deadline = $('#' + deadline)[0];
    var price = $('#' + price)[0];
    
    if (err_array['deadline']) {
        let error = createErrorMessage(err_array['deadline']);
        deadline.after(error);
        deadline.classList.add("is-invalid");
    }
    if (err_array['price']) {
        let error = createErrorMessage(err_array['price']);
        price.after(error);
        price.classList.add("is-invalid");
    }
    
}
// APPROVE OFFICER DECISION
$('#approve_button_officer').on('click', function(e) {
    e.preventDefault();
    let id = $('#ticket_id_approve_officer').val();
    let officer_approval = $('#officer_approval_value').val();
    let token = $('#token_approve_officer').val();
    let equipment_id = $('#equipment_select').val();
    let deadline = $('#deadline_approve_officer').val();
    let price = $('#price_approve_officer').val();
    let officer_remarks = $('#officer_remarks_approve').val();

    $.ajax({
        'url': '/tickets/update2/' + id,
        'type': 'PUT',
        'data': {id:id, officer_approval:officer_approval, equipment_id:equipment_id, deadline:deadline, price:price, officer_remarks:officer_remarks, _token:token},
        'success': (res) => {
            console.log('success');
            window.location.reload();
        },
        'error': (res) => { 
            
        // remove all errors 
        $('.invalid-feedback').remove();
        $(".is-invalid").removeClass('is-invalid');

        let errors = res['responseJSON']['errors'];
        let err_array = [];

        // get error messages and push them into an array
        for (let key in errors) {
            err_array[key] = (errors[key][0]);
        }            

        handleErrorsOfficer(err_array, 'deadline_approve_officer', 'price_approve_officer'); 
    } 

    });
});

// EDIT OFFICER DECISION
$('#submit_btn_update_officer').on('click', function(e) {
    e.preventDefault();
    let id = $('#id_edit_officer').val();
    let officer_approval = $('#officer_approval_select').val();
    let equipment_id = $('#equipment_select_update_officer').val();
    let deadline = $('#deadline_edit_officer').val();
    let price = $('#price_edit_officer').val();
    let officer_remarks = $('#officer_remarks_edit').val();
    let token = $('#token_edit_officer').val();

    console.log(id, officer_approval, equipment_id, deadline, price, officer_remarks, token);
    $.ajax({
        'url': '/tickets/update-officer-decision/' + id,
        'type': 'PUT',
        'data': {id:id, officer_approval:officer_approval, equipment_id:equipment_id, deadline:deadline, price:price, officer_remarks:officer_remarks, _token:token},
        'success': (res) => {
            console.log('success', res);
            window.location.reload();
        },
        'error': (res) => { 
            // console.log(res);
        // remove all errors 
        $('.invalid-feedback').remove();
        $(".is-invalid").removeClass('is-invalid');

        let errors = res['responseJSON']['errors'];
        console.log(errors);
        let err_array = [];

        // get error messages and push them into an array
        for (let key in errors) {
            err_array[key] = (errors[key][0]);
        }            

        handleErrorsOfficer(err_array, 'deadline_edit_officer', 'price_edit_officer'); 
        }
    }); 
});

// MARK FINISHED

function handleErrorsFinished(err_array) {
    let date_finished = $('#date_finished_mark_finished')[0];

    if (err_array['date_finished']) {
        let error = createErrorMessage(err_array['date_finished']);
        date_finished.after(error);
        date_finished.classList.add("is-invalid");
    }
}

$('#btn_submit_mark_finished').on('click', function(e) {
    e.preventDefault();
    let id = $('#id_mark_finished').val();
    let token = $('#token_mark_finished').val();
    let status_id = $('#status_id_mark_finished').val();
    let equipment_id = $('#equipment_select1').val();
    let serial_number_id;
    if ($('#serial_number_select1').val() != null) {
        serial_number_id = $('#serial_number_select1').val();
    } else if ($('#serial_number_select2').val() != null) {
        serial_number_id = $('#serial_number_select2').val();
    }
    let final_remarks = $('#final_remarks_mark_finished').val();
    let date_finished = $('#date_finished_mark_finished').val();

    $.ajax({
        'url': '/tickets/update4/' + id,
        'type': 'PUT',
        'data': {id:id, status_id:status_id, equipment_id:equipment_id, serial_number_id:serial_number_id, date_finished:date_finished, _token:token, final_remarks:final_remarks},
        'success': (res) => {
            console.log('success', res);
            window.location.reload();
        },
        'error': (res) => { 
        // console.log(res);
        // remove all errors 
        $('.invalid-feedback').remove();
        $(".is-invalid").removeClass('is-invalid');

        let errors = res['responseJSON']['errors'];
        console.log(errors);
        let err_array = [];

        // get error messages and push them into an array
        for (let key in errors) {
            err_array[key] = (errors[key][0]);
        }            

        handleErrorsFinished(err_array); 
        }
    }); 
    // console.log(id, token, status_id, equipment_id, serial_number_id, date_finished);

});
