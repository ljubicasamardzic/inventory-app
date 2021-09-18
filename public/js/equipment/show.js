// ajax is used for serial numbers since the modal would otherwise close when an error occurs
// this way ajax keeps the modal open and displays the error messages 

function handleErrorMessages(err_array) {
    let elements = $('input[name="serial_numbers[]"]');

    for (let key in err_array) {
        // the message is in the form serial_numbers.0 so we just take the index so we know where to add the message 
        let index = key.split(".")[1];
        let message = err_array[key][0];

    let error_message = document.createElement('p');

    error_message.innerHTML = message;
    error_message.setAttribute("style", "color: red;");
    error_message.classList.add('invalid-message');

    // add the message after the input field
    elements[index].after(error_message);

    // make the input field with an error visible
    elements[index].classList.add("is-invalid");
    } 
}

function getInputValues(e) {
    e.preventDefault();

    var values = [];
    $('input[name="serial_numbers[]"]').each(function(){
         values.push($(this).val());
    });

    let equipment_id = $('#equipment_id').val();
    let token = $('#token_serial_numbers').val();

    $.ajax({
        'url': '/serial-numbers',
        'type': 'POST',
        'data': {serial_numbers:values, equipment_id:equipment_id, _token:token},
        'success': (res) => {
            window.location.reload();
        },
        'error': (res) => {

            // console.log(res);
        // remove all errors 
        $('.invalid-message').remove();
        $("input.is-invalid").removeClass('is-invalid');

        let errors = res['responseJSON']['errors'];
        // let err_array = [];

        // get error messages and push them into an array
        // for (let key in errors) {
        //     err_array.push(errors[key][0]);
        // } 

        // console.log(res['responseJSON']['errors']);
        handleErrorMessages(errors);
       } 
    });
}

// form for adding serial numbers 
const form = $('#serial_numbers_form');
form.on('submit', getInputValues);

// for deleting serial nums
$('.confirm-delete-btn').on('click', function(e) {
    e.stopPropagation();
    e.preventDefault();
    // getting the id from the button that caused the event to fire
    let id = $(this).attr('data-id');
    console.log($(this), id);
    swal({
        title: 'Are you sure?',
        text: "The action is irreversible.",
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

$('.clickable-row').click((e) => {
    window.location.href = $(e.currentTarget).data('href');
});

$('.callModal').on('click', function(event) {
    let button = $(this) // Button that triggered the modal
    let id = button.attr('data-id')
    let sn = button.attr('data-sn')
    let form = $('#serial_numbers_edit_form')
    form.attr("action", "/serial-numbers/" + id)
    $('#sn_id').val(id)
    $('#sn_num').val(sn)
});