/**
 * Управление доступом полей формы
 *
 * @param formName
 * @param disable
 */
window.onEdit = function (formName, disable) {

    var form = $('form[data-form-name="' + formName + '"]');
    var btnEdit = $(form).find('div[data-role="edit"]');
    var btnCancel = $(form).find('div[data-role="cancel"]').show();
    var btnSendForm = $(form).find("button");

    $(form).find('select, textarea, input').prop('disabled', Boolean(disable));

    if (Boolean(disable)) {
        btnCancel.hide();
        btnSendForm.hide();
        btnEdit.show()
    } else {
        btnCancel.show();
        btnSendForm.show();
        btnEdit.hide();
    }
}

/**
 * Управление поиском
 */
window.searchToggle = function () {
    var containerSearch = $('#search');
    containerSearch.slideToggle('slow')
}


/**
 * Проверка фильтра на наличие не пустых полей, и добавление выделения
 */
window.checkSearch = function () {
    const formField = $('form.check__field').find('select, input');
    formField.each(function (i, item) {
        let el = $(item);
        if (el.val() !== '') {
            el.addClass('border-primary')
        }
    })
}
checkSearch();


window.sort = function (el, column = null) {
    var element = $(el);

    switch (true) {
        case !element.hasClass('sort-asc') && !element.hasClass('sort-desc') :
            console.log('sort-asc');
            break;

        case element.hasClass('sort-asc'):
            console.log('sort-desc');
            break;

        case element.hasClass('sort-desc') :
            console.log('off sort');
            break;

    }
}

$('.sidebar-content .nav-item').each(function () {
    const location = window.location.protocol + "//" + window.location.host + window.location.pathname;
    if ($(this).children('div').find('a').attr('href') == location) {
        $(this).children('div').addClass('show')

    }
});

$('a[href="' + location + '"]').addClass('menu-active')


// подтверждение выхода
window.exitConfirm = function () {
    var res = confirm('Вы действительно хотите выйти?')
    if (!res) {
        event.preventDefault();
    }
}

// подтверждение выхода
window.deleteConfirm = function () {
    var res = confirm('Вы действительно хотите удалить?')
    if (!res) {
        event.preventDefault();
    }
}

jQuery(window).on("load", function () {
    window.loadUserActive();
    setInterval(() => {
        window.loadUserActive()
    }, 1000 * 60)
});

window.loadUserActive = function () {
    $.ajax({
        url: '/user-active',
        method: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done((res) => {
        const userList = res.html;
        const container = $('.user-list-activity .userList');
        const countNotifications = $('#countActiveUsers')
        container.empty().append(userList);
        countNotifications.text(res.count);
    })
}

window.setAmountFormat = function (el) {
    const input = $(el);
    const amount = number_format(input.val(), 2, '.', ' ');
    input.next('div.amount-format').text(amount)
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

window.saveAudio = new Audio('./../audio/save.mp3');


