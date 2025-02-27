const modalEdit = $('#edit_lid');
const modalBody = modalEdit.find('.modal-body');

// подгружаем в модалку форму редактирвоания
modalEdit.on('show.bs.modal', function (e) {
    const loading = "<div class=\"d-flex justify-content-center align-items-center flex-column\">\n" +
        "                    <div class=\"loading text-center mb-3\">\n" +
        "                        Загрузка ...\n" +
        "                    </div>\n" +
        "                </div>";

    var id = $(e.relatedTarget).data('id');

    modalBody.empty();
    modalBody.append(loading)

    $.ajax({
        url: getLitInfoURL,
        method: 'GET',
        data: {id: id},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done((res) => {
        if (res.result) {
            modalBody.empty();
            modalBody.html(res.html)
            $('.select2-with-color').select2({
                templateSelection: window.formatState,
                templateResult: window.formatState
            })
        } else {
            showNotification('error', res.message)
        }
    })
})

// очищаем модалку
modalEdit.on('hidden.bs.modal', function () {
    modalBody.empty();
})

window.showNotification = function (status, message) {

    let alertSuccess = $('.ajax-success');
    let alertError = $('.ajax-error');

    alertSuccess.hide();
    alertError.hide();

    switch (status) {
        case 'success' :
            alertSuccess.text(message).show();
            break;
        case 'error' :
            alertError.text(message).show();
            break;
    }

    setTimeout(() => {
        alertSuccess.hide();
        alertError.hide();
    }, 4000);
}


