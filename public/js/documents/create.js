// I edited the function so that it could be used both for adding and editing serial numbers on the same page
// hence two different selects and the flag used to distinguish between the two  

function addSerialNumbers(flag = false){

    let equipment_id = '';
    let serial_num_id = '';

    if (flag == true) {
        equipment_id = $('#chosen_equipment_id').val();
        serial_num_id = $('#chosen_serial_num_id').val();
    } else {
        equipment_id = $("#equipment_select").val();
        if(equipment_id == ''){
            $("#serial_number_select").html('');
            return;
        }    
    }

    $.ajax({
       'url' : '/equipment-serial-numbers/' + equipment_id,
       'type' : 'GET',
       'success': (response) => {
        let serial_numbers = response;
        let options = '';
        serial_numbers.forEach((number) => {
            let selected = '';
            if(serial_num_id && serial_num_id == number.id) selected = 'selected';
            options += `<option value=\"${number.id}\" ${selected}>${number.serial_number}</option>`;
        });
        flag == true ? $("#serial_number_select_2").html(options) : $("#serial_number_select").html(options);
       }
    });
}

$(document).on('click', '#btn-return-item', function(e){
    if ($(this).hasClass('disabled')) {
        e.preventDefault();
    }
});

$('#edit_btn').on('click', () => {
    let equipment_id = $('#edit_btn').data("id");
    console.log(equipment_id);
});

// for editing serial numbers in document items 

function findId(item_id) {
 
    let equipment_id = $('#edit_btn_' + item_id).attr('data-equipment-id');
    let serial_num_id = $('#edit_btn_' + item_id).attr('data-serial-number-id');
    $('#chosen_equipment_id').val(equipment_id);
    $('#chosen_serial_num_id').val(serial_num_id);
    $('#relevant_document_item_id').val(item_id);
}

$('#edit_item_modal').on('show.bs.modal', () => {
    addSerialNumbers(true);
});

$('#edit_item_modal').on('hide.bs.modal', () => {
    $("#serial_number_select_2").html('');
});

