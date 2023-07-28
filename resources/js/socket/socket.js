window.socket = io.connect('http://192.168.11.129:7000');

window.getSocket = function () {
    return window.socket;
};

// если получено событие PushNotification, вызываем функцию обновлени уведомслений
window.socket.on('PushNotification', (e) => {
    window.updateNotificationList();
})
