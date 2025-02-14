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

/**
 * Подгрузка списка статей по датам в всплывающем окне
 */
$('form#form-search-article').submit(function (event) {
    event.preventDefault();
    let params = $(this).serialize();
    let table = $('#create_file_report table tbody');
    let button = $(this).find('button');
    let totalArticle = $('#total-article');

    if (window.ajaxStatus) {
        window.ajaxStatus = false;
        button.attr('disabled', true);
        $.ajax({
            url: getArticleListURL,
            method: 'GET',
            data: params,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done((res) => {
            if (res.result) {
                table.empty().append(res.html);
                totalArticle.text(res.total);
            } else {
                showNotification('error', 'Произошла ошибка запроса.')
            }
            button.attr('disabled', false);
            window.ajaxStatus = true;
        }).fail((error) => {
            button.attr('disabled', false);
            console.log(error)
            window.ajaxStatus = true;
        })
    } else {
        alert('Дождитесь завершения запроса');
    }
})

/**
 * управление всеми чекбоксами от главного
 */
$('#create_file_report .main-checkbox').change(function () {
    let mainCheckbox = $(this);

    if (mainCheckbox.is(':checked')) {
        $('#create_file_report table input[type="checkbox"]').prop('checked', true);
    } else {
        $('#create_file_report table input[type="checkbox"]').prop('checked', false);
    }
});

$('form#form-table-article button').click(function (e) {
    e.preventDefault()
    let form = $('form#form-table-article');
    let count = 0;
    form.find('tbody').find('input[type="checkbox"]').each(function (i, el) {
        if ($(el).is(':checked')) {
            count++;
        }
    })

    if (count > 0) {
        form.submit();
    } else {
        showNotification('error', 'Нет выбранных статей.');
    }
})
