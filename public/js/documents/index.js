$('.clickable-row').click((e) => {
    window.location.href = $(e.currentTarget).data('href');
});

$('.delete-confirm').on('click', function (e) {
    e.stopPropagation();
    e.preventDefault();
    const id = $(this).attr('data-id');
    form = $('#delete-form-' + id);
    swal({
        title: 'Are you sure?',
        text: "Deleting the document will delete all tickets and equipment related to it.",
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
                form.submit();
        }
    });
});

