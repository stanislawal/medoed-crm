window.editStatusProject = function (el, url) {
    const statusId = $(el).val();
    ajax('post', url, {status_id: statusId})
}

window.editMoodProject = function (el, url) {
    const moodId = $(el).val();
    ajax('post', url, {mood_id: moodId})
}

window.editCommentProject = function (el, url) {
    const comment = $(el).val();
    ajax('post', url, {comment: comment})
}

window.editStatusTextProject = function (el, url) {
    const projectStatusText = $(el).val();
    ajax('post', url, {project_status_text: projectStatusText})
}

window.editDateLastChangeProject = function (el, url) {
    const lastChange = $(el).val();
    ajax('post', url, {date_last_change: lastChange})
}

window.editDatePayment = function (el, url) {
    const value = $(el).val();
    const columnName = $(el).attr('name')
    ajax('post', url, {[columnName]: value})
}


window.editCheckProject = function (el, url) {
    let check = 0;

    if ($(el).is(":checked")) {
        check = 1;
    }

    ajax('post', url, {check: check});
}

window.editStatusPaymentProject = function (el, url) {
    const value = $(el).val();
    const columnName = $(el).attr('name')
    ajax('post', url, {[columnName]: value})
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
