/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/project/files.js ***!
  \***************************************/
window.ajaxStatus = true;
window.saveFile = function (e, projectId, url) {
  if (ajaxStatus) {
    window.ajaxStatus = false;
    var loadFile = e.target.files[0];
    var inputFile = $(e.target);
    var formData = new FormData();
    formData.append('file', loadFile);
    formData.append('project_id', projectId);
    $.ajax({
      url: url,
      type: 'POST',
      contentType: false,
      processData: false,
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (response) {
      if (response.result) {
        window.showFileList(response.html);
      } else {
        window.showNotification('error', response.message);
        console.log(response.message);
      }
      window.ajaxStatus = true;
      inputFile.val(null);
    }).fail(function (error) {
      console.log(error);
      window.ajaxStatus = true;
      inputFile.val(null);
    });
  }
};
window.deleteFile = function (projectId, url) {
  var formData = new FormData();
  formData.append('project_id', projectId);
  $.ajax({
    url: url,
    type: 'POST',
    contentType: false,
    processData: false,
    data: formData,
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done(function (response) {
    if (response.result) {
      window.showFileList(response.html);
      window.showNotification('success', 'Файл успешно удален.');
    }
    window.ajaxStatus = true;
  }).fail(function (error) {
    console.log(error);
    window.ajaxStatus = true;
  });
};
window.showFileList = function (html) {
  $('.container__files').empty().html(html);
};
window.showNotification = function (status, message) {
  var alertSuccess = $('.ajax-success');
  var alertError = $('.ajax-error');
  alertSuccess.hide();
  alertError.hide();
  switch (status) {
    case 'success':
      alertSuccess.text(message).show();
      break;
    case 'error':
      alertError.text(message).show();
      break;
  }
  setTimeout(function () {
    alertSuccess.hide();
    alertError.hide();
  }, 4000);
};
/******/ })()
;