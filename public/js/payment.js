/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/js/payment/payment.js ***!
  \*****************************************/
window.edit = function (className) {
  var tr = $('tr.' + className);
  tr.find('select, input, textarea').prop('disabled', false);
  tr.find('.edit').hide();
  tr.find('.save').show();
};
window.save = function (className) {
  var moder = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var tr = $('tr.' + className);
  var url = tr.attr('data-url');
  var attr = {
    'status_payment_id': tr.find('select[name="status_payment_id"]').val(),
    'sber_d': tr.find('input[name="sber_d"]').val(),
    'sber_k': tr.find('input[name="sber_k"]').val(),
    'privat': tr.find('input[name="privat"]').val(),
    'um': tr.find('input[name="um"]').val(),
    'wmz': tr.find('input[name="wmz"]').val(),
    'birja': tr.find('input[name="birja"]').val(),
    'comment': tr.find('textarea[name="comment"]').val()
  };
  if (moder) {
    var mark = 0;
    if (tr.find('input[name="mark"]').is(':checked')) {
      mark = 1;
    }
    attr.mark = mark;
  }
  console.log(attr);
  ajax('post', url, attr);
  tr.find('.edit').show();
  tr.find('.save').hide();
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
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (res) {
      if (res.result) {
        showNotification('success', 'Данные успешно обновлены.');
      } else {
        showNotification('error', res.message);
      }
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
$('select[name="project_id"]').change(function () {
  var className = $(this).attr('data-class');
  var option = $(this).find(':selected');
  var client = option.attr('data-client');
  var author = option.attr('data-author');
  $('.' + className + ' .td-client').text(client);
  $('.' + className + ' .td-author').text(author);
});
window.ajaxStatus = true;
window.getSelect = function (el) {
  var url = '/payment/select-article/' + $(el).val();
  if (window.ajaxStatus) {
    var selectBlock = $('.select-block');
    selectBlock.prop('disabled', true);
    $.ajax({
      url: url,
      method: 'get',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (res) {
      if (res.result) {
        selectBlock.empty();
        selectBlock.append(res.html);
      } else {
        showNotification('error', res.message);
      }
      window.ajaxStatus = true;
    }).fail(function (error) {
      showNotification('error', 'Произошла ошибка запроса.');
      console.log(error);
      window.ajaxStatus = true;
    });
    selectBlock.prop('disabled', false);
  } else {
    alert('Дождитесь завершения запроса');
  }
};
/******/ })()
;