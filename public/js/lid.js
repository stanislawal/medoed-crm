/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/lid/lid.js ***!
  \*********************************/
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
var modalEdit = $('#edit_lid');
var modalBody = modalEdit.find('.modal-body');

// подгружаем в модалку форму редактирвоания
modalEdit.on('show.bs.modal', function (e) {
  var loading = "<div class=\"d-flex justify-content-center align-items-center flex-column\">\n" + "                    <div class=\"loading text-center mb-3\">\n" + "                        Загрузка ...\n" + "                    </div>\n" + "                </div>";
  var id = $(e.relatedTarget).data('id');
  modalBody.empty();
  modalBody.append(loading);
  $.ajax({
    url: getLitInfoURL,
    method: 'GET',
    data: {
      id: id
    },
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }).done(function (res) {
    if (res.result) {
      modalBody.empty();
      modalBody.html(res.html);
      $('.select2-with-color').select2({
        templateSelection: window.formatState,
        templateResult: window.formatState
      });
    } else {
      showNotification('error', res.message);
    }
  });
});

// очищаем модалку
modalEdit.on('hidden.bs.modal', function () {
  modalBody.empty();
});
$('table input, table select, table textarea').change(function () {
  var _this = this;
  var url = $(this).closest('tr').data('url');
  var name = $(this).prop('name');
  var value = '';
  if ($(this).prop('type') === 'checkbox') {
    value = $(this).is(':checked') ? 1 : 0;
  } else {
    value = $(this).val();
  }
  $(this).prop('disabled', true);
  $.ajax({
    url: url,
    method: 'POST',
    data: _defineProperty({}, name, value),
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
$('body').on('change', '#call_up_id', function () {
  var resultCall = $('#result_call');
  var textarea = resultCall.find('textarea');
  var value = $(this).val();
  if (parseInt(value) === 2) {
    resultCall.show();
    textarea.prop('required', true);
  } else {
    resultCall.hide();
    textarea.prop('required', false);
  }
});
/******/ })()
;