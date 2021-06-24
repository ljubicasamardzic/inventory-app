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
    
        // REQUEST STATUSES
        // const PENDING = 1;
        // const APPROVED = 2;
        // const REJECTED = 3;

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

// edit user equipment modal 
// did not cover errors since these input fields do not have any particular rules seeing that they are nullable

$('#submit_btn_edit_equipment').on('click', function(e) {
e.preventDefault();
alert('click');

let ticket_id = $(this).attr('data-id');
let ticket_type = $('#ticket_type').val();
let ticket_request_type = $('#ticket_request_type_id').val();
let description_supplies = $('#supplies_desc').val();
let quantity = $('#supplies_quantity').val();
let equipment_category_id = $('#equipment_category_id').val();
let description_equipment = $('#description_equipment').val();
let token = $('#token_edit_equipment').val();

// console.log(ticket_id, ticket_type, ticket_request_type, description_supplies, quantity, equipment_category_id, description_equipment, token);

    $.ajax({
            'url': '/tickets/' + ticket_id,
            'type': 'PUT',
            'data': {ticket_type:ticket_type, ticket_request_type:ticket_request_type, description_supplies:description_supplies, 
                quantity:quantity, equipment_category_id:equipment_category_id, description_equipment:description_equipment, _token:token},
            'success': (res) => {
                window.location.reload();
            },
            'error': (res) => {            
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
function handleErrorsOfficerApproval(err_array) {
    var deadline = $('#deadline_approve_officer')[0];
    var price = $('#price_approve_officer')[0];
    
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
// approve request officer
$('#approve_button_officer').on('click', function(e) {
    e.preventDefault();
    let id = $('#ticket_id_approve_officer').val();
    let officer_approval = $('#officer_approval_value').val();
    let token = $('#token_approve_officer').val();
    let equipment_id = $('#equipment_select').val();
    let deadline = $('#deadline_approve_officer').val();
    let price = $('#price_approve_officer').val();
    let officer_remarks = $('#officer_remarks_approve').val();

    // console.log(id, officer_approval, token, equipment_id, deadline, price, officer_remarks);

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

        handleErrorsOfficerApproval(err_array); 
    } 

});
});




