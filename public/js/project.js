/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/js/project/project.js ***!
  \*****************************************/
window.editStatusProject = function (el, url) {
  var statusId = $(el).val();
  ajax('post', url, {
    status_id: statusId
  });
};
window.editCommentProject = function (el, url) {
  var comment = $(el).val();
  ajax('post', url, {
    comment: comment
  });
};
window.editDateLastChangeProject = function (el, url) {
  var lastChange = $(el).val();
  ajax('post', url, {
    date_last_change: lastChange
  });
};
window.editCheckProject = function (el, url) {
  var check = 0;
  if ($(el).is(":checked")) {
    check = 1;
  }
  ajax('post', url, {
    check: check
  });
};
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