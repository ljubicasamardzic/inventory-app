// FOR ASSIGNING EQUIPMENT WITH PARTICULAR SERIAL NUMBERS 
function availableSerialNums() {

    let equipment_id = $("#equipment_select").val();
    if(equipment_id == ''){
        $("#serial_number_select").html('');
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
        $("#serial_number_select").html(options);
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






