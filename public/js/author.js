/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./resources/js/reports/author.js ***!
  \****************************************/
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
window.updateData = function (el, url) {
  var input = $(el);
  var column = input.attr('name');
  var value = input.val();
  var params = _defineProperty({
    '_method': 'PUT'
  }, column, value);
  ajax('POST', url, params);
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
window.updateAuthorPayment = function (el, url) {
  var column = $(el).attr('name');
  var value = $(el).val();
  ajax('POST', url, _defineProperty({}, column, value));
};

/**
 * Подгрузка списка статей по датам в всплывающем окне
 */
$('form#form-search-article').submit(function (event) {
  event.preventDefault();
  var params = $(this).serialize();
  var table = $('#create_file_report table tbody');
  var button = $(this).find('button');
  var totalArticle = $('#total-article');
  if (window.ajaxStatus) {
    window.ajaxStatus = false;
    button.attr('disabled', true);
    $.ajax({
      url: getArticleListURL,
      method: 'GET',
      data: params,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (res) {
      if (res.result) {
        table.empty().append(res.html);
        totalArticle.text(res.total);
      } else {
        showNotification('error', 'Произошла ошибка запроса.');
      }
      button.attr('disabled', false);
      window.ajaxStatus = true;
    }).fail(function (error) {
      button.attr('disabled', false);
      console.log(error);
      window.ajaxStatus = true;
    });
  } else {
    alert('Дождитесь завершения запроса');
  }
});

/**
 * управление всеми чекбоксами от главного
 */
$('#create_file_report .main-checkbox').change(function () {
  var mainCheckbox = $(this);
  if (mainCheckbox.is(':checked')) {
    $('#create_file_report table input[type="checkbox"]').prop('checked', true);
  } else {
    $('#create_file_report table input[type="checkbox"]').prop('checked', false);
  }
});
$('form#form-table-article button').click(function (e) {
  e.preventDefault();
  var form = $('form#form-table-article');
  var count = 0;
  form.find('tbody').find('input[type="checkbox"]').each(function (i, el) {
    if ($(el).is(':checked')) {
      count++;
    }
  });
  if (count > 0) {
    form.submit();
  } else {
    showNotification('error', 'Нет выбранных статей.');
  }
});
/******/ })()
;