window.socket = io.connect('https://medoed-crm.ru:7000');

window.getSocket = function () {
    return window.socket;
};

window.socket.on("connect", () => {
    console.log("WebSocket connect");
});

// если получено событие PushNotification, вызываем функцию обновлени уведомслений
window.socket.on('PushNotification', (e) => {
    window.updateNotificationList();
});
