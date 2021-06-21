// deleting docs
$('.confirm-delete-btn').on('click', function(e) {
    e.stopPropagation();
    e.preventDefault();
    // getting the id from the button that caused the event to fire
    let id = $(this).attr('data-id');
    swal({
        title: 'Are you sure?',
        text: "The action will also delete serial numbers, tickets and document items related to this equipment.",
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
