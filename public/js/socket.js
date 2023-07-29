/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./resources/js/socket/socket.js ***!
  \***************************************/
window.socket = io.connect('http://localhost:7000');
window.getSocket = function () {
  return window.socket;
};
window.socket.on("connect", function () {
  console.log("WebSocket connect");
});

// если получено событие PushNotification, вызываем функцию обновлени уведомслений
window.socket.on('PushNotification', function (e) {
  window.updateNotificationList();
});
/******/ })()
;