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
        console.log(response);
        let options = '';
        serial_numbers.forEach((number) => {
            let selected = '';
            if(serial_num_id && serial_num_id == number.id) selected = 'selected';
            options += `<option value=\"${number.id}\" ${selected}>${number.serial_number}</option>`;
        });
        flag == true ? $("#serial_number_select_2").append(options) : $("#serial_number_select").append(options);
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
    let serial_num_value = $('#edit_btn_' + item_id).attr('data-val');
    $('#chosen_equipment_id').val(equipment_id);
    if (serial_num_value != null) {
        $('#chosen_serial_num_id').val(serial_num_id);
        let opt = `<option value=\"${serial_num_id}\" selected>${serial_num_value}</option>`;
        $('#serial_number_select_2').append(opt);
    }
    $('#relevant_document_item_id').val(item_id);

    // adding the current value to the modal since the ajax function returns only currently available serial numbers
    // and bc of this, does not pick up the current value

}

$('#edit_item_modal').on('show.bs.modal', () => {
    addSerialNumbers(true);
});

$('#edit_item_modal').on('hide.bs.modal', () => {
    $("#serial_number_select_2").html('');
});

$('.return-equipment-btn').on('click', function(e) {
    // e.stopPropagation();
    // e.preventDefault();
    // getting the id from the button that caused the event to fire
    let id = $(this).attr('data-id');
    console.log(id)
    swal({
        title: 'Are you sure?',
        text: "This action is irreversible.",
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
            $("#return_equipment_"+ id).submit();
        }
    });
});

