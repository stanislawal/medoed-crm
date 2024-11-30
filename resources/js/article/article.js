window.edit = function (className) {
    var tr = $('tr.' + className);
    tr.find('select, input, div').prop('disabled', false);
    tr.find('.edit').hide();
    tr.find('.save').show();
}

window.save = function (className) {
    var tr = $('tr.' + className);
    var url = tr.attr('data-url');

    let check = 0;
    if (tr.find('input[name="check"]').is(':checked')) {
        check = 1;
    }

    var attr = {
        '_method': 'PUT',
        'check': check,
        'article': tr.find('input[name="article"]').val(),
        'without_space': tr.find('input[name="without_space"]').val(),
        'price_client': tr.find('input[name="price_client"]').val(),
        'id_currency': tr.find('select[name="id_currency"]').val(),
        'price_author': tr.find('input[name="price_author"]').val(),
        'link_text': tr.find('input[name="link_text"]').val(),
        'project_id': tr.find('select[name="project_id"]').val(),
        'authors_id': tr.find('select[name="select_authors"]').val(),
        'redactors_id': tr.find('select[name="select_redactors[]"]').val(),
        'price_redactor': tr.find('input[name="price_redactor"]').val(),
        'manager_id': tr.find('select[name="manager_id"]').val(),

        'is_fixed_price_client': tr.find('input[name="is_fixed_price_client"]').is(':checked') ? 1 : 0,
        'is_fixed_price_author': tr.find('input[name="is_fixed_price_author"]').is(':checked') ? 1 : 0,
        'is_fixed_price_redactor': tr.find('input[name="is_fixed_price_redactor"]').is(':checked') ? 1 : 0
    };

    ajax('post', url, attr);

    // tr.find('.edit').show();
    // tr.find('.save').hide()
    // tr.find('select, input').prop('disabled', true);
};


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

$('select[name="project_id"]').change(function () {
    var className = $(this).attr('data-class');
    var option = $(this).find(':selected');
    var client = option.attr('data-client');
    var author = option.attr('data-author');

    $('.' + className + ' .td-client').text(client);
    $('.' + className + ' .td-author').text(author);
});

