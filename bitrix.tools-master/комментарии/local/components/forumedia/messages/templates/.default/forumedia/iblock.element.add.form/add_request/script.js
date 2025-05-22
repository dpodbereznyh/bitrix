$(document).ready(function(){
    let valStal=0;
    let addStar1=function (){
        $('.comment-modal .star-act').removeClass('star-act');
        $('.comment-modal .star-act').addClass('star');
        $('.comment-modal .star-1').addClass('star-act');
    }
    let addStar2=function (){
        $('.comment-modal .star-act').removeClass('star-act');
        $('.comment-modal .star-act').addClass('star');
        $('.comment-modal .star-2').addClass('star-act');
    }
    let addStar3=function (){
        $('.comment-modal .star-act').removeClass('star-act');
        $('.comment-modal .star-act').addClass('star');
        $('.comment-modal .star-3').addClass('star-act');
    }
    let addStar4=function (){
        $('.comment-modal .star-act').removeClass('star-act');
        $('.comment-modal .star-act').addClass('star');
        $('.comment-modal .star-4').addClass('star-act');
    }
    let addStar5=function (){
        $('.comment-modal .star-act').removeClass('star-act');
        $('.comment-modal .star-act').addClass('star');
        $('.comment-modal .star-5').addClass('star-act');
    }
    let addStar=function (){
        if($('.comment-modal .input-stars').val()==1)addStar1();
        else if($('.comment-modal .input-stars').val()==2)addStar2();
        else if($('.comment-modal .input-stars').val()==3)addStar3();
        else if($('.comment-modal .input-stars').val()==4)addStar4();
        else if($('.comment-modal .input-stars').val()==5)addStar5();
        else { $('.comment-modal .star-act').removeClass('star-act');
            $('.comment-modal .star-act').addClass('star');}
    }
    $('body').on('click','.lbl-1',function (){
        addStar1();
        $('.comment-modal .info-stars').html('1 звезда');
        $('.comment-modal .input-stars').val(1);
    })
    $('body').on('click','.lbl-2',function (){
        addStar2();
        $('.comment-modal .info-stars').html('2 звезды');
        $('.comment-modal .input-stars').val(2);
    })
    $('body').on('click','.lbl-3',function (){
        addStar3();
        $('.comment-modal .info-stars').html('3 звезды');
        $('.comment-modal .input-stars').val(3);
    })
    $('body').on('click','.lbl-4',function (){
        addStar4();
        $('.comment-modal .info-stars').html('4 звезды');
        $('.comment-modal .input-stars').val(4);
    })
    $('body').on('click','.lbl-5',function (){
        addStar5();
        $('.comment-modal .info-stars').html('5 звезд');
        $('.comment-modal .input-stars').val(5);
    })

    let addTofile=function (id)
    {
    let txt= "" +
           '<div className="col-12">'+
                '<div><input type="hidden" name="PROPERTY[315]['+id+']" value=""/>'+
                    '<input id="send_'+id+'" type="file" name="PROPERTY_FILE_316_'+id+'" accept="image/*" data-id="'+id+'"/></div>'+
            '</div>';
        $('.drug-drop').append(txt);
    }

    $('body').on('change','.drug-drop [type="file"]',function(){
        let flg=true;
        let max=1;
        console.log($(this).val());
        $('.drug-drop [type="file"]').each(function(){
            if($(this).val()==''){flg=false;}
            if($(this).data('id')>max){max=$(this).data('id');}
        })
        console.log(flg,max);
        if(flg){addTofile(max+1);}
    });
        //addTofile(2);

    $('body').on('mouseover','.lbl-1,.lbl-2,.lbl-3,.lbl-4,.lbl-5',function (){
        if($(this).hasClass('lbl-1')){
            addStar1();
        } else
        if($(this).hasClass('lbl-2')){
            addStar2();
        } else
        if($(this).hasClass('lbl-3')){
            addStar3();
        } else
        if($(this).hasClass('lbl-4')){
            addStar4();
        }
        if($(this).hasClass('lbl-5')){
            addStar5();
        }
    })
    $('body').on('mouseout','.stars',function (){
        addStar()
    })
})