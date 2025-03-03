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

$('tr td input[name="write_lid"]').change(function () {
    var url = $(this).data('url');
    var value = $(this).is(':checked') ? 1 : 0;

    $(this).prop('disabled', true);

    $.ajax({
        url: url,
        method: 'POST',
        data: {'write_lid': value},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done((res) => {
        if (res.result) {
            showNotification('success', 'Поле успешно обновлено.', true)
        } else {
            showNotification('error', res.message)
        }
    }).fail((error) => {
        showNotification('error', 'Произошла ошибка.')
        $(this).is(':checked') ? $(this).prop('checked', false) : $(this).prop('checked', true);
    })

    $(this).prop('disabled', false);
})

window.showNotification = function (status, message, audio = false) {

    let alertSuccess = $('.ajax-success');
    let alertError = $('.ajax-error');

    alertSuccess.hide();
    alertError.hide();

    switch (status) {
        case 'success' :
            alertSuccess.text(message).show();
            if (audio) {
                window.saveAudio.play();
            }
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




