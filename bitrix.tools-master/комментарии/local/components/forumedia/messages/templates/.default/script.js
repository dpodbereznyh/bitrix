$(document).ready(function(){
    $('.js-add-comment').on('click',function(){
        $('.comment-modal').removeClass('hidden');
        $('.wrapper1 .header_wrap').css('z-index','4');
    })

    $('body').on('click','.comment-more',function(){
        $.post('/ajax/mess.php',[
            {name: 'IBLOCK_ID',value:C_IBLOCK_ID},
            {name: 'ID',value:C_ID},
            {name: 'AJAX',value:'Y'},
            {name: 'commentSort',value:$(this).data('sort')},
            {name: 'page',value:$(this).data('page')},
            {name: 'psize',value:$(this).data('psize')},
        ]).done(function(result){
            $('.comment-more').remove();
            $('.comments-block-ajax').append(result);
        })
    })

    $('.comment-sort').on('click',function(){
        $.post('/ajax/mess.php',[
            {name: 'IBLOCK_ID',value:C_IBLOCK_ID},
            {name: 'ID',value:C_ID},
            {name: 'AJAX',value:'Y'},
            {name: 'commentSort',value:$(this).data('sort')},
            {name: 'page',value:'1'},
            {name: 'psize',value:'10'},
        ])
    .done(function(result)
        {
            console.log($('.comment-sort').data('sort'));
        if($('.comment-sort').data('sort')=='asc'){
            $('.comment-sort').data('sort','desc');
            }else{$('.comment-sort').data('sort','asc');}
            $('.comments-block-ajax').html(result);
        })
    })
    $('body').on('click','.icon-plus',function(){
        let tag=$(this);
        $.post(C_PATH+'/ajax.php',[{name: 'IBLOCK_ID',value:C_IBLOCK_ID},{name: 'ID',value:$(this).data('id')},{name: 'VALUE',value:'plus'}])
            .done(function(result)
        {if(result!='')
            $('.comment-item_count-plus',tag.closest('.block-plus-minus')).html(result);
        })
    })
    console.log(C_PATH+'/ajax.php');

    $('body').on('click','.icon-minus',function(){
        let tag=$(this);
        $.post(C_PATH+'/ajax.php',[{name: 'IBLOCK_ID',value:C_IBLOCK_ID},{name: 'ID',value:$(this).data('id')},{name: 'VALUE',value:'minus'}])
    .done(function(result)
        {if(result!='')
            $('.comment-item_count-minus',tag.closest('.block-plus-minus')).html(result);
        })
    })
})