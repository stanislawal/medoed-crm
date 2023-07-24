window.ajaxStatus = true;

window.browseNotification = function(el, url) {
    const itemNotification = $(el).parent('.notification-item');
    const list = itemNotification.parent('.accordion-body');
    const emptyPatter = '<div class="text-center text-12 fst-italic w-100 p-3 text-gray">Пусто</div>';
    itemNotification.remove()

    if (window.ajaxStatus) {
        window.ajaxStatus = false;
        $.ajax({
            url: url,
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        }).done((res) => {
            if (res.result) {
                itemNotification.remove()
                const count = list.children('.notification-item').length;
                if(count === 0){
                    list.append(emptyPatter)
                }
                window.ajaxStatus = true;
            }
        });
    }
}
