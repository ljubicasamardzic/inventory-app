
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

let select = $('#ticket_request_type_id_equipment')
let supplies_div = $('#supplies_div')
let equipment_div =  $('#equipment_div')
let equipment_cat = $('#equipment_category_equipment_request')
let equipment_desc = $('#equipment_desc_equipment_request')
let supplies = $('#supplies_desc_equipment_request')
let quantity_div = $('#quantity_equipment_request')

function emptyFields() {
    equipment_cat.val('')
    equipment_desc.val('')
    supplies.val('')
    quantity_div.val('')
}

select.on('change', () => {
    if (select.val() == '1') {
        showAndHide(equipment_div, supplies_div)
        emptyFields()
    } else if (select.val() == '2') {
        showAndHide(supplies_div, equipment_div)
        emptyFields()
    } else if (select.val() == '') {
        showAndHide(equipment_div, supplies_div, true)
        emptyFields()
    }
});

function viewPassword(password, status) {
    // check which exact field should be shown/hidden
    let password_text =  $('#' + password);
    // check if password should be shown in plain text or not
    let showStatus = $('#' + status);

    if (password_text.css('-webkit-text-security') == 'none' ) {
        password_text.css('-webkit-text-security', 'disc');
        showStatus.className='fa fa-eye-slash';
    } else {
        password_text.css('-webkit-text-security', 'none');
        showStatus.className='fa fa-eye';
    }
}

function createErrorMessage(error_text) {
    let error_message = document.createElement('p');
    error_message.innerHTML = error_text;
    error_message.setAttribute("style", "color: red;");
    error_message.classList.add('invalid-feedback');
    return error_message;
}
function handleErrorsNewRequest(err_array) {
    let description_malfunction_div = $('#description_malfunction')[0];
    let  document_item_slt = $('#document_item_id')[0];

    if (err_array['description_supplies']) {
        let error = createErrorMessage(err_array['description_supplies']);
        supplies[0].after(error);
        supplies[0].classList.add("is-invalid");
    }
    if (err_array['quantity']) {
        let error = createErrorMessage(err_array['quantity']);
        quantity_div[0].after(error);
        quantity_div[0].classList.add("is-invalid");
    }
    if (err_array['equipment_category_id']) {
        let error = createErrorMessage(err_array['equipment_category_id']);
        equipment_cat[0].after(error);
        equipment_cat[0].classList.add("is-invalid");
    }
    if (err_array['description_equipment']) {
        let error = createErrorMessage(err_array['description_equipment']);
        equipment_desc[0].after(error);
        equipment_desc[0].classList.add("is-invalid");
    }
    if (err_array['document_item_id']) {
        let error = createErrorMessage(err_array['document_item_id']);
        document_item_slt.after(error);
        document_item_slt.classList.add("is-invalid");
    }
    if (err_array['description_malfunction']) {
        let error = createErrorMessage(err_array['description_malfunction']);
        description_malfunction_div.after(error);
        description_malfunction_div.classList.add("is-invalid");
    }

}

// submit equipment or supplies request 
$('#submit_btn_new_equipment').on('click', function(e) {
    e.preventDefault();

    let token = $('#token_new_equipment_request').val();
    let ticket_type = $('#ticket_type_equipment_request').val();
    let ticket_request_type = select.val();
    let description_supplies = supplies.val();
    let quantity = quantity_div.val();
    let equipment_category_id = equipment_cat.val();
    let description_equipment = equipment_desc.val();

    $.ajax({
        'url' : '/tickets',
        'type' : 'POST',
        'data': {ticket_type:ticket_type, ticket_request_type:ticket_request_type, description_supplies:description_supplies, quantity:quantity, equipment_category_id:equipment_category_id, description_equipment:description_equipment, _token:token},
        'success': (res) => {
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

        handleErrorsNewRequest(err_array); 
        } 
    });

});

// submit repair request 
$('#submit_btn_repair_equipment').on('click', function(e) {
    e.preventDefault();

    let token_repair = $('#token_repair_request').val();
    let ticket_type_repair = $('#ticket_type_repair').val();
    let request_type_repair = $('#ticket_request_type_repair').val();
    let document_item_id = $('#document_item_id').val();
    let description_malfunction = $('#description_malfunction').val();

    // console.log(document_item_id);
    // console.log(token_repair, ticket_type_repair, request_type_repair, document_item_id, description_malfunction);
    $.ajax({
        'url' : '/tickets',
        'type' : 'POST',
        'data': {ticket_type:ticket_type_repair, ticket_request_type:request_type_repair, document_item_id:document_item_id, description_malfunction:description_malfunction, _token:token_repair},
        'success': (res) => {
            // console.log('success', res);
            window.location.reload();
        },
        'error': (res) => { 
            // console.log('error', res);
        // remove all errors 
        $('.invalid-feedback').remove();
        $(".is-invalid").removeClass('is-invalid');

        let errors = res['responseJSON']['errors'];
        let err_array = [];

        // get error messages and push them into an array
        for (let key in errors) {
            err_array[key] = (errors[key][0]);
        }            

        handleErrorsNewRequest(err_array); 
        } 
    });
});