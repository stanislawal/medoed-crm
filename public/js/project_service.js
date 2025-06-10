/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************************!*\
  !*** ./resources/js/project_service/project_service.js ***!
  \*********************************************************/
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
$('table input, table select, table textarea').change(function () {
  var _data,
    _this = this;
  var url = $(this).closest('tr').data('url');
  var name = $(this).prop('name');
  var value = '';
  if ($(this).prop('type') === 'checkbox') {
    value = $(this).is(':checked') ? 1 : 0;
  } else {
    value = $(this).val();
  }
  $(this).prop('disabled', true);
  console.log(url, name, value);
  $.ajax({
    url: url,
    method: 'POST',
    data: (_data = {}, _defineProperty(_data, name, value), _defineProperty(_data, '_method', 'put'), _data),
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done(function (res) {
    if (res.result) {
      showNotification('success', 'Поле успешно обновлено.', true);
    } else {
      showNotification('error', res.message);
    }
  }).fail(function (error) {
    showNotification('error', 'Произошла ошибка.');
    $(_this).is(':checked') ? $(_this).prop('checked', false) : $(_this).prop('checked', true);
  });
  $(this).prop('disabled', false);
});
window.showNotification = function (status, message) {
  var audio = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
  var alertSuccess = $('.ajax-success');
  var alertError = $('.ajax-error');
  alertSuccess.hide();
  alertError.hide();
  switch (status) {
    case 'success':
      alertSuccess.text(message).show();
      if (audio) {
        window.saveAudio.play();
      }
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