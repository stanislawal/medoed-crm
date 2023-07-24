/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************************!*\
  !*** ./resources/js/notification/notification.js ***!
  \***************************************************/
window.ajaxStatus = true;
window.browseNotification = function (el, url) {
  var notificationItem = $(el).parent('.notification-item');
  var list = notificationItem.parent('.accordion-body');
  var emptyPatter = '<div class="text-center text-12 fst-italic w-100 p-3 text-gray">Пусто</div>';
  var countNotificationType = notificationItem.parent('.accordion-body').parent('.accordion-collapse').parent('.accordion-item').children('.accordion-header').children('button').children('span').children('strong');
  if (window.ajaxStatus) {
    window.ajaxStatus = false;
    $.ajax({
      url: url,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(function (res) {
      if (res.result) {
        notificationItem.slideToggle(200, 'linear', function () {
          notificationItem.remove();
          var count = Number(list.children('.notification-item').length);
          countNotificationType.text(count);
          if (count === 0) {
            list.append(emptyPatter);
          }
          window.changeCountNotification();
        });
        window.ajaxStatus = true;
      }
    });
  }
};

/**
 * Изменение общего количества заявок
 */
window.changeCountNotification = function () {
  var sensorCountNotification = $('.count-notification');
  var countNotification = Number(sensorCountNotification.text());
  var newCount = countNotification - 1;
  sensorCountNotification.text(newCount < 0 ? 0 : newCount);
};
/******/ })()
;