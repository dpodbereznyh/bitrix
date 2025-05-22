//функция записи в куки
function writeCookie(name, val, expires) {
    var date = new Date;
    date.setDate(date.getDate() + expires);
    document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
}
//функция чтения куки
function readCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

//список элементов для сравнения
var compare_list = '';
if(readCookie('compare_list')){
    compare_list = readCookie('compare_list');
}

//добавление элемента в сравнение
$(document).on('click', '.duibi', function (){
    if($(this).attr('data-id')){
        if(compare_list != ''){
            compare_list += ','+$(this).attr('data-id');
        }else{
            compare_list = $(this).attr('data-id');
        }

        //пишем в куки элементы сравнения на 30 дней
        writeCookie('compare_list', compare_list, 30);
    }
})

//удаления элемента из сравнения
$(document).on('click', '.canceldb', function (){
    if(compare_list != '' && $(this).attr('data-id')){
        var compare_list_new_arr = [];
        var element_id = $(this).attr('data-id');
        compare_list_arr = compare_list.split(',');
        $.each(compare_list_arr, function(index, val){
            if(parseInt(val) != parseInt(element_id)){
                compare_list_new_arr[index] = val
            }
        })
    }

    //пишем в куки элементы сравнения на 30 дней
    if(compare_list_new_arr){
        compare_list = compare_list_new_arr.join(',');
        writeCookie('compare_list', compare_list_new_arr.join(','), 30);
    }else{
        compare_list = '';
        writeCookie('compare_list', compare_list, 30);
    }
})

//очистка сравнения
$(document).on('click', '.clean_compare', function (){
    compare_list = '';
    writeCookie('compare_list', compare_list, 30);
})