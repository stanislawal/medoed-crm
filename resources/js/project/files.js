window.ajaxStatus = true;

window.saveFile = function (e, projectId, url) {
    if (ajaxStatus) {
        window.ajaxStatus = false;
        let loadFile = e.target.files[0];
        const inputFile = $(e.target);
        const formData = new FormData();
        formData.append('file', loadFile);
        formData.append('project_id', projectId);


        $.ajax({
            url: url,
            type: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done((response) => {
            if (response.result) {
                window.showFileList(response.html)
            }else{
                window.showNotification('error', response.message)
                console.log(response.message)
            }
            window.ajaxStatus = true;
            inputFile.val(null);
        }).fail((error) => {
            console.log(error)
            window.ajaxStatus = true;
            inputFile.val(null);
        });
    }
}

window.deleteFile = function (projectId, url) {

    const formData = new FormData();
    formData.append('project_id', projectId);

    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        processData: false,
        data: formData,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done((response) => {
        if (response.result) {
            window.showFileList(response.html)
            window.showNotification('success', 'Файл успешно удален.')
        }
        window.ajaxStatus = true;
    }).fail((error) => {
        console.log(error)
        window.ajaxStatus = true;
    });
}

window.showFileList = function (html) {
    $('.container__files').empty().html(html);
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

    setTimeout(()=>{
        alertSuccess.hide();
        alertError.hide();
    }, 4000);
}
