$(document).ready(function() {
    var updBasket = function() {
        let action = 'CNTBASKET';
        let comm = [];
        comm.push({
            name: 'action',
            value: action
        });
        $.post('/local/components/forumedia/basket/basket.php', comm).done(function(ret) {
            //$('.result-val__count .value').html(ret);
        });

    }
    $('.basket').on('click', '.all_check', function() {
        let val = $(this).prop("checked");
        $('.basket_chk').prop("checked", val);
    })
    $('.basket').on('click', 'a.up', function() {
        let val = $('.cnt', $(this).closest('.table-row')).val();
        $('.cnt', $(this).closest('.table-row')).val(+val + 1);
        $('.cnt', $(this).closest('.table-row')).trigger('change');
    })
    $('.basket').on('click', 'a.down', function() {
        let val = $('.cnt', $(this).closest('.table-row')).val();
        if (val > 1) {
            $('.cnt', $(this).closest('.table-row')).val(+val - 1);
            $('.cnt', $(this).closest('.table-row')).trigger('change');
        }
    })
    $('.basket').on('change', '.cnt', function() {
        if (!/^(0|[1-9]\d*)$/.test($(this).val())) {
            $(this).val(1);
        }
        let action = 'setCountBasket';
        let cnt = $(this).val();
        let id = $(this).closest('.table-row').data('id');
        let tr = $(this).closest('.table-row');
        let comm = [];
        comm.push({
            name: 'ID',
            value: id
        });
        comm.push({
            name: 'QUANTITY',
            value: cnt
        });
        comm.push({
            name: 'action',
            value: 'setCountBasket'
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
        /*comm.push({
            name: 'OUT_LID',
            value: 's1'
        });*/
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
          
            $('.item_price_total', tr).html(ret.ITEMS[id].PRICES.PRINT_FINAL_PRICE);
            $('.total_product_price_display_front').html(ret.PRINT_BASE_PRICE)
            $('#oversea_total_product_discount_price_front').html(ret.PRINT_DISCOUNT_PRICE)
            $('#oversea_total_order_price_front .value').html(ret.PRINT_PRICE)
            $('.result-val__weight .value').html((+(ret.WEIGHT)/1000)+' кг.');
            $('.result-val__count .value').html(ret.QUANTITY);
           
        });
    })
    $('.basket').on('click', '.btn-del', function() {
        let action = 'removeFromBasket';
        let id = $(this).closest('.table-row').data('id');
        let tr = $(this).closest('.table-row');
        let comm = [];
        comm.push({
            name: 'ID',
            value: id
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
        /*comm.push({
            name: 'OUT_LID',
            value: 's1'
        });*/
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
           
            if ($.isEmptyObject(ret.ITEMS[id])) { //location.reload()};
                tr.remove();
                $('.total_product_price_display_front').html(ret.PRINT_BASE_PRICE)
                $('#oversea_total_product_discount_price_front').html(ret.PRINT_DISCOUNT_PRICE)
                $('#oversea_total_order_price_front .value').html(ret.PRINT_PRICE)
                $('.result-val__weight .value').html((+(ret.WEIGHT)/1000)+' кг.');
                $('.result-val__count .value').html(ret.QUANTITY);
               
            }
            if ($.isEmptyObject(ret.ITEMS)){
               location.reload(); 
            }
            updBasket();
            $('.xans-order-normoverseatitle.title b').html($('.basket tr.xans-record-').length);
        });
    })
    $('.basket').on('click', '.basket-clear', function() {
        let action = 'clearBasket';
        let comm = [];
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
        /*comm.push({
            name: 'OUT_LID',
            value: 's1'
        });*/
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
            //if ($.isEmptyObject(ret.ITEMS)) {
                location.reload()
            //};
            //updBasket();
        });
    })
    $('.basket').on('click', '.btn-chk-del', function() {
        let action = 'removeFromBasket';
        let comm = [];
        $('.basket .basket_chk:checked').each(function(index) {
            comm.push({
                name: 'ID[]',
                value: $(this).val()
            });
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
        
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
            let i=0;
            $('.basket .table-row').each(function() {
                if ($.isEmptyObject(ret.ITEMS[$(this).data('id')])) {
                    $(this).remove();

                }else{
                    ++i;
                };
            })
            $('.total_product_price_display_front').html(ret.PRINT_BASE_PRICE)
            $('#oversea_total_product_discount_price_front').html(ret.PRINT_DISCOUNT_PRICE)
            $('#oversea_total_order_price_front .value').html(ret.PRINT_PRICE)
            $('.xans-element-.title h3 b').html(i);
            updBasket();
            $('.xans-order-normoverseatitle.title b').html($('.basket tbody tr.xans-record-').length);
        });
    })

    $('.basket').on('click', '.btn-wish', function() {
        setTimeout(function(){location.reload();},500); 
    })

    $('.basket').on('click', '.btn-order', function() {
        let action = 'getCheckBasket';
        let comm = [];
        let id = $(this).closest('tr').data('id');
        comm.push({
            name: 'ID[]',
            value: id
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
       
        
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
            if(ret['RESULT']){
                window.location.href=/order/;}
            if(ret['MESSAGE'])
                windowMess(ret['MESSAGE'],false); 
        });
    })

$('.basket').on('click', '.buy-chk', function() {
        let action = 'getCheckBasket';
        let comm = [];
         $('.basket .basket_chk:checked').each(function(index) {
            comm.push({
                name: 'ID[]',
                value: $(this).val()
            });
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
        
        
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
           
            if(ret['RESULT']){
                window.location.href=/order/;}
            if(ret['MESSAGE'])
                windowMess(ret['MESSAGE'],false); 
        });
    })

$('.basket').on('click', '.buy-all', function() {
        let action = 'getCheckBasket';
        let comm = [];
         $('.basket .basket_chk').each(function(index) {
            if($(this).val()){
            comm.push({
                name: 'ID[]',
                value: $(this).val()
            });}
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 's1'
        });
      
       
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
            
            if(ret['RESULT']){
                window.location.href=/order/;}
            if(ret['MESSAGE'])
                windowMess(ret['MESSAGE'],false); 
        });
    })


$('body').on('mouseenter','.td-articul',function(){

    let id=$(this).closest('.table-row').data('id');
console.log(id);
$('.img-block [data-id='+id+']').show();
});

$('body').on('mouseleave','.td-articul',function(){
let id=$(this).closest('.table-row').data('id');
$('.img-block [data-id='+id+']').hide();
});

})