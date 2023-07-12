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

    if(Boolean(disable)){
        btnCancel.hide();
        btnSendForm.hide();
        btnEdit.show()
    }else{
        btnCancel.show();
        btnSendForm.show();
        btnEdit.hide();
    }
}

/**
 * Управление поиском
 */
window.searchToggle = function(){
    var containerSearch = $('#search');
    containerSearch.slideToggle('slow')
}


/**
 * Проверка фильтра на наличие не пустых полей, и добавление выделения
 */
window.checkSearch = function(){
    const formField = $('form.check__field').find('select, input');
    formField.each(function(i, item){
       let el = $(item);
       if(el.val() !== ''){
           el.addClass('border-primary')
       }
    })
}
checkSearch();


window.sort = function(el, column = null){
    var element = $(el);

    switch (true){
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

$('.nav-item').each(function(){

    const location = window.location.protocol + "//" + window.location.host + window.location.pathname;

    if($(this).children('div').find('a').attr('href') == location){
        $(this).children('div').addClass('show')

    }

});

$('a[href="'+location+'"]').addClass('menu-active')
