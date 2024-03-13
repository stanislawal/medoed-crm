window.updateData = function (el, url) {
    const input = $(el);

    const column = input.attr('name');
    const value = input.val();

    const params = {
        '_method': 'PUT',
        [column]: value
    };

    ajax('POST', url, params);
}

window.ajaxStatus = true;
window.ajax = function (method, url, params) {
    if (window.ajaxStatus) {
        window.ajaxStatus = false;
        $.ajax({
            url: url,
            method: method,
            data: params,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done((res) => {
            showNotification('success', 'Данные успешно обновлены.')
            console.log(res)
            window.ajaxStatus = true;
        }).fail((error) => {
            showNotification('error', 'Произошла ошибка запроса.')
            console.log(error)
            window.ajaxStatus = true;
        })
    } else {
        alert('Дождитесь завершения запроса');
    }
}
window.showNotification = function (status, message) {

    let alertSuccess = $('.ajax-success');
    let alertError = $('.ajax-error');

    alertSuccess.hide();
    alertError.hide();

    switch (status) {
        case 'success' :
            alertSuccess.text(message).show();
            window.saveAudio.play();
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

window.updateAuthorPayment = function (el, url) {
    const column = $(el).attr('name');
    const value = $(el).val();

    ajax('POST', url, {
        [column]: value
    });
}
