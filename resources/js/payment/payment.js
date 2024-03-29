window.edit = function (className) {
    var tr = $('tr.' + className);
    tr.find('select, input, textarea').prop('disabled', false);
    tr.find('.edit').hide();
    tr.find('.save').show();
}

window.save = function (className, moder = false) {
    var tr = $('tr.' + className);
    var url = tr.attr('data-url');


    var attr = {
        'date': tr.find('input[name="date"]').val(),
        'status_payment_id': tr.find('select[name="status_payment_id"]').val(),
        'tinkoff_a': tr.find('input[name="tinkoff_a"]').val(),
        'tinkoff_k': tr.find('input[name="tinkoff_k"]').val(),
        'sber_a': tr.find('input[name="sber_a"]').val(),
        'sber_d': tr.find('input[name="sber_d"]').val(),
        'sber_k': tr.find('input[name="sber_k"]').val(),
        'privat': tr.find('input[name="privat"]').val(),
        'um': tr.find('input[name="um"]').val(),
        'wmz': tr.find('input[name="wmz"]').val(),
        'birja': tr.find('input[name="birja"]').val(),
        'comment': tr.find('textarea[name="comment"]').val()
    };

    if (moder) {
        let mark = 0;

        if (tr.find('input[name="mark"]').is(':checked')) {
            mark = 1;
        }

        attr.mark = mark
    }

    if (moder) {
        let back_duty = 0;

        if (tr.find('input[name="back_duty"]').is(':checked')) {
            back_duty = 1;
        }

        attr.back_duty = back_duty
    }

    console.log(attr);

    ajax('post', url, attr);

    tr.find('.edit').show();
    tr.find('.save').hide()
    tr.find('select, input, textarea').prop('disabled', true);
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
            if (res.result) {
                showNotification('success', 'Данные успешно обновлены.')
            } else {
                showNotification('error', res.message)
            }
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

$('input[name="mark"]').change(function () {
    const mark = $(this)
    const select = mark.parent('div').parent('td').parent('tr').children('td').children('div').children('select');
    if (mark.is(':checked')) {
        select.children('option[value="4"]').prop('selected', true)
        // $('#select option[value=2]').prop('selected', true);
    }

});

