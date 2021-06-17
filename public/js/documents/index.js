$('.clickable-row').click((e) => {
    window.location.href = $(e.currentTarget).data('href');
});


function fillModal(id, event) {

    event.stopPropagation();

    let form = $('#delete-doc-form');
    form.attr("action", "/documents/" + id);

    $('#delete-doc-modal').modal('show');

}

