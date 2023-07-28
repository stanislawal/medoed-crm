/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/socket/socket.js ***!
  \***************************************/
window.socket = io.connect('http://192.168.11.129:7000');
window.getSocket = function () {
  return window.socket;
};

// если получено событие PushNotification, вызываем функцию обновлени уведомслений
window.socket.on('PushNotification', function (e) {
  window.updateNotificationList();
});
/******/ })()
;