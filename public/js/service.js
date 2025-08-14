/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/js/reports/service.js ***!
  \*****************************************/
window.ajaxStatus = true;
window.ajax = function (method, url, params) {
  if (window.ajaxStatus) {
    window.ajaxStatus = false;
    $.ajax({
      url: url,
      method: method,
      data: params,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (res) {
      showNotification('success', 'Данные успешно обновлены.');
      console.log(res);
      window.ajaxStatus = true;
    }).fail(function (error) {
      showNotification('error', 'Произошла ошибка запроса.');
      console.log(error);
      window.ajaxStatus = true;
    });
  } else {
    alert('Дождитесь завершения запроса');
  }
};
window.showNotification = function (status, message) {
  var alertSuccess = $('.ajax-success');
  var alertError = $('.ajax-error');
  alertSuccess.hide();
  alertError.hide();
  switch (status) {
    case 'success':
      alertSuccess.text(message).show();
      window.saveAudio.play();
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