$(document).ready(function() {
    /*var updBasket = function() {
        let action = 'CNTBASKET';
        let comm = [];
        comm.push({
            name: 'action',
            value: action
        });
        $.post('/local/components/forumedia/basket/basket.php', comm).done(function(ret) {
            $('.result-val__count .value').html(ret);
        });
    }

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
    })
*/

$('[name="PROPERTIES[PHONE]"]').mask("+7(999)999-9999", {autoclear: false});

jQuery.validator.addMethod("checkMask", function(value, element) {
    return /\+\d{1}\(\d{3}\)\d{3}-\d{4}/g.test(value); 
});

    $("#basic-form").validate({
rules: {
'PROPERTIES[INN]' : {
required: true,
minlength: 3
},
'PROPERTIES[EMAIL]' : {
required: true,
minlength: 3,
email: true
},
'PROPERTIES[PHONE]' : {
required: true,
minlength: 3,
checkMask: true
},
'PROPERTIES[CONTACT_PERSON]' : {
required: true,
minlength: 3
},
'PROPERTIES[INN]' : {
required: true,
minlength: 3
},
'PROPERTIES[COMPANY]' : {
required: true,
minlength: 3
},
'PROPERTIES[ADDRESS]' : {
required: true,
minlength: 3
},
},
 messages: {
    'PROPERTIES[PHONE]': {
      checkMask: "Введите полный номер телефона"
    }
  }
});

    var postForm = function() 
    {
        let data;
        if($('#basic-form').valid())
        {data=$('#basic-form').serializeArray();}else{
            data=false;
        }
        return data;
    }

    var formWrite = function(res) 
    {

    }


$('.main-order input[type=text],.main-order input[type=mail]').on('change',
    function(){
        let data=postForm();
        data.push({
            name: 'action',
            value: 'refresh'
        });
         $.post('/local/components/forumedia/order/ajax.php', data).done(function(ret) {
           formWrite();
        });
})

$('.button-item a,.button-order a').on('click',
    function(){
        flg_send=$('#basic-form').valid();
        if(flg_send)
        {let data=new FormData($('#basic-form')[0]);
        data.append('action', 'orderSave');
        data.append('LID', 's1');
       
        $.ajax({
    url: '/local/components/forumedia/order/ajax.php',
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
    success: function(ret){
       if(ret.ORDER_ID)window.location.href="/order/new_order.php?ORDER_ID="+ret.ORDER_ID;
    }
});}
})

    $('.prop-file input[type=file]').on('change', function(){
    let file = this.files[0];
    $(this).closest('.prop-file').find('.input-file-text').html(file.name);
});

    $('.prop-shipment label').on('click',function(){

if($(this).attr('for')=='SHIPMENT_ID_1'){$('.address-sclad').addClass('hidden');$('.prop-address').removeClass('hidden');}
if($(this).attr('for')=='SHIPMENT_ID_2'){$('.prop-address').addClass('hidden');$('.address-sclad').removeClass('hidden');}

    });

 var updBasket = function() {
        let action = 'CNTBASKET';
        let comm = [];
        comm.push({
            name: 'action',
            value: action
        });
        $.post('/local/components/forumedia/basket/basket.php', comm).done(function(ret) {
            $('.count-item .value').html(ret);
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
       
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
            console.log(ret)
           $('.item_price_total', tr).html(ret.ITEMS[id].PRICES.PRINT_FINAL_PRICE);
            $('.weight-item .value').html((+(ret.WEIGHT)/1000)+' кг.');
            $('.result-item .value').html(ret.PRINT_PRICE);
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
            $('.weight-item .value').html((+(ret.WEIGHT)/1000)+' кг.');
            $('.result-item .value').html(ret.PRINT_PRICE);
            }
            if ($.isEmptyObject(ret.ITEMS)){
               location.reload(); 
            }
            updBasket();
            $('.xans-order-normoverseatitle.title b').html($('.basket tr.xans-record-').length);
        });
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



  

})