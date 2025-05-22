<script>
var add_fav=function(id){
    let temp=$.cookie('fav-exm');
    let favor=new Array();
    favor=(temp)?temp.split(','):[];
    favor.push(id.toString());
    let favor1 = favor.filter((item, index) => {
        return favor.indexOf(item) === index});
    //str=JSON.stringify(favor1);
    console.log(favor1);
    $.cookie('fav-exm', favor1,{ expires: 7, path: '/' });
}

var clear_fav=function(){
    $.cookie('fav-exm', '');
}

$(document).ready(function(){
    let num=get_fav();
    $('.b-topline-favorite__count.js-favorite-count').html(num.length);
    console.log(num,'num');
    $('[data-js_favorite]').each(function(){
        change_name($(this).data('js_favorite').toString());
    })
    
    $('[data-js_favorite]').on('click',function(){

        let id=$(this).data('js_favorite').toString();
        if(!in_array(id)){add_fav(id);
        }else{del_fav(id);}
        let num=get_fav();
        $('.b-topline-favorite__count.js-favorite-count').html(num.length);
        change_name(id);
    })
})


var get_fav=function(){
    let temp=$.cookie('fav-exm');
    let favor=[];
    favor=(temp)?temp.split(','):[];
    return favor; 
}

var del_fav=function(id){
    let arr=get_fav();
    ind=arr.indexOf(id);
    console.log(ind);
    if(ind!=-1)arr.splice(ind, 1);
    $.cookie('fav-exm', arr,{ expires: 7, path: '/' });
}

var in_array=function(id){
    let arr=get_fav();
    ind=arr.indexOf(id);
    return ind!=-1;
}

var change_name=function(id){
    text='В избранное';
    if(in_array(id))
        text='В избранном';
    else
        text='В избранное';
    $('.favorite-link__text' ,$('[data-js_favorite='+id+']')).html(text);
}
</script>