<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js");
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick.css");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick-theme.css");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.min.css");

if(!empty($arResult)){
?>

<div class="redcodeFW_bold page-title-h2">Примеры работ</div>
<div class="slick-sl"><?
foreach($arResult[0]['UF_IMAGE'] as $item)
{
 $file = CFile::ResizeImageGet($item, array('width'=>237, 'height'=>237), BX_RESIZE_IMAGE_EXACT, true); 
?>
<div class="image" id="<?=$item["AREA_ID"]?>"><a href="<?=CFile::GetPath($item)?>" target="_blank" class="fancybox" data-fancybox-group="gallery">
                        <img src="<?=$file['src']?>" alt="<?=$arResult["NAME"]?>"/>
                    </a>
                </div>
                
                
<?  
}
?></div>

<script>
$(document).ready(function() {



$('.slick-sl').slick({
  dots: false,
  infinite: false,
  cssEase: 'linear',
  autoplay: true,
  autoplaySpeed: 5000,
        slidesToShow: 4,
        slidesToScroll: 1,
   responsive: [
          {
              breakpoint: 1189,
              settings: {
                  slidesToShow: 3
              }
             
          },
           { breakpoint: 860,
              settings: {
                  slidesToShow: 2
              }
          }

          ,
           { breakpoint: 576,
              settings: {
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


