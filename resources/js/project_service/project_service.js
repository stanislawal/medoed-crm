$('table input, table select, table textarea').change(function () {

    var url = $(this).closest('tr').data('url');
    var name = $(this).prop('name');
    var value = '';

    if ($(this).prop('type') === 'checkbox') {
        value = $(this).is(':checked') ? 1 : 0;
    } else {
        value = $(this).val();
    }

    $(this).prop('disabled', true);

    console.log(url, name, value)

    $.ajax({
        url: url,
        method: 'POST',
        data: { [name] : value, '_method': 'put' },
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

});

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
