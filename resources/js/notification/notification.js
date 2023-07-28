window.ajaxStatus = true;
/**
 *  Прочитать уведомление
 */
window.browseNotification = function (el, url) {
    const notificationItem = $(el).parent('.notification-item');
    const list = notificationItem.parent('.accordion-body');
    const emptyPatter = '<div class="text-center text-12 fst-italic w-100 p-3 text-gray">Пусто</div>';
    const countNotificationType = notificationItem
        .parent('.accordion-body')
        .parent('.accordion-collapse')
        .parent('.accordion-item')
        .children('.accordion-header')
        .children('button')
        .children('span')
        .children('strong');

    if (window.ajaxStatus) {
        window.ajaxStatus = false;
        $.ajax({
            url: url,
            method: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done((res) => {
            if (res.result) {
                notificationItem.slideToggle(200, 'linear', () => {
                    notificationItem.remove();
                    const count = Number(list.children('.notification-item').length);
                    countNotificationType.text(count);
                    if (count === 0) {
                        list.append(emptyPatter)
                    }
                    window.changeCountNotification();
                })
                window.ajaxStatus = true;
            }
        });
    }
}

/**
 * Изменение общего количества заявок
 */
window.changeCountNotification = function () {
    const sensorCountNotification = $('.count-notification');
    const countNotification = Number(sensorCountNotification.text());
    const newCount = (countNotification - 1);
    sensorCountNotification.text(newCount < 0 ? 0 : newCount);
}

/**
 * Обновление списка уведомлений
 */
window.updateNotificationList = function () {
    const url = $('meta[name="url-update-notification"]').attr('content');
    $.ajax({
        url: url,
        method: 'GET',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done((res) => {
        if(res.result){
            const html = res.html;
            const count = res.count;

            const sensorCountNotification = $('.count-notification');
            sensorCountNotification.text(count);

            const notificationList = $('.notification-list');
            notificationList.empty().html(html);

            // const myAudio = $('#sound-push');
            // myAudio[0].play();
        }
    });
}


