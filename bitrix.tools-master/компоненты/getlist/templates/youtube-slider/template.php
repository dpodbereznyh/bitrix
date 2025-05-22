<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));
CJSCore::Init(array("popup"));
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js");
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick.css");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick-theme.css");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.min.css");

if(!empty($arResult)){
?>

<div class="title_block"><h3>Видео</h3>
<a href="https://www.youtube.com/channel/UC9LkDfwWwtNZxQhjhhkHD2g/videos" class="right_link_block">Все видео</a>
</div>


<div class="slick-sl"><?
foreach($arResult as $item)
{
// $file = CFile::ResizeImageGet($item['DETAIL_PICTURE'], array('width'=>237, 'height'=>237), BX_RESIZE_IMAGE_EXACT, true); 
?>
<div class="image" id="<?=$item["AREA_ID"]?>" data-id="<?=$item['PROPERTIES']['VIDEO_ID']['VALUE']?>">
                        <img src="<?=$item['PROPERTIES']['IMAGE']['VALUE']?>" alt="<?=$item["NAME"]?>"/>
                        <div class="video-slick__iconplay"></div>
                    
                </div>
                
                
<?  
}
?>

<script>

$(document).ready(function() {
var ifg=0;
$('.image').on('click',function(e){
  e.stopPropagation();
  windowMess(video_str($(this).data('id')));
})  

var video_str=function(id){
 return '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+id+'/?autoplay=1&autohide=1" frameborder="0" allowfullscreen allow="autoplay"></iframe>';
}

var windowMess = function(message, messTitle = '') {
   // BX.ready(function() {
     ifg++;
      console.log('sss');
      if($("#popup-message-youtube").length==0){
        var popup = new BX.PopupWindow("popup-message-youtube", null, {
            content: '<span style="font-size:16px">' +message+ '</span>',
           
            zIndex: 100, // z-index
            closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 0
            },
            titleBar: messTitle,
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: false, // можно двигать или нет
            resizable: false, // можно ресайзить
            min_height: 100, // минимальная высота окна
            min_width: 300, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
            // angle: true, // появится уголок
            overlay: {
                // объект со стилями фона
                backgroundColor: 'black',
                opacity: 500
            },
            events: {
                onPopupShow: function() {
                    // Событие при показе окна
                    //$('#popup-window-content-popup-message-youtube').html(''); 
                   // $('#popup-window-content-popup-message-youtube').html(message);
                },
                onPopupClose: function() {
                    // Событие при закрытии окна 
                    this.destroy();               
                }
            }
        });
        
       popup.show();}
   // });
}

$('.slick-sl').slick({
  dots: false,
  infinite: false,
  cssEase: 'linear',
  autoplay: true,
  autoplaySpeed: 10000,
        slidesToShow: 2,
        slidesToScroll: 2,
   responsive: [
          {
              breakpoint: 1189,
              settings: {
                  slidesToShow: 2,
                  slidesToScroll: 2,
              }
             
          },
           { breakpoint: 860,
              settings: {
                  slidesToShow: 1,
                   slidesToScroll: 1,
              }
          }

          ,
           { breakpoint: 576,
              settings: {
                slidesToScroll: 1,
                  slidesToShow: 1
              }
          }
      ]      
});

$("a.fancybox").fancybox();

$('.slick-sl').on('afterChange', function(event,slick,currentSlide){
  
    $("a.fancybox").fancybox();
  
});

});
</script>
<?

}


