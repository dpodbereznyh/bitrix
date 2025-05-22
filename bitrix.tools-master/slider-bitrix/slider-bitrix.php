//------------template.php---------------------------------------------------------------------
<div class="slick-sl"><?
foreach($arResult['PROPERTIES']['MORE_PHOTO']['VALUE'] as $key=>$item)
{
$file = CFile::ResizeImageGet($item, array('width'=>400, 'height'=>400), BX_RESIZE_IMAGE_EXACT, true); 
?>
<div>
					<div class="image">
					<a href="<?=CFile::GetPath($item)?>" target="_blank" class="fancybox" data-fancybox-group="gallery">
						<img src="<?=$file['src']?>" alt="<?=$arResult["NAME"]?>"/></a>
					</div>
				</div>
<?
	}
?></div>

//----------component_epilog------------------------------------------------------------------------
<?
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js");
//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick.css");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick-theme.css");
//Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.min.css");
?>
<script>
$(document).ready(function() {

//$("a.fancybox").fancybox();

$('.slick-sl').slick({
  dots: true,
  infinite: true,
  speed: 300,
  fade:true,
  cssEase: 'linear',
  autoplay: false,
  autoplaySpeed: 2000,
});

});
</script>


//-----css-----------------
.slick-sl
{
width:400px;
margin:20px auto;
}

.slick-dots li button.slick-next
{

}

.slick-dots li button.slick-prev
{

}

button.slick-prev:before, 
button.slick-next:before
{
color:#ff6f02;
}

.body ul.slick-dots li:before{
display:none;
}

@media (max-width: 992px)
{
.slick-sl
{
width:300px;
	}
}

@media (max-width: 540px)
{
.slick-sl
{
width:230px;
margin:20px auto;
	}
}


/**
*  Расширеный слайдер
*
**/

//------------template.php---------------------------------------------------------------------
<div class="slick-sl"><?
                                foreach($GLOBALS['PROPS']['B_SLIDER'] as $key=>$item)
                                {
                                    $file = CFile::ResizeImageGet($item, array('width'=>400, 'height'=>400), BX_RESIZE_IMAGE_EXACT, true);
                                    ?>
                                    <div>
                                        <div class="image">
                                            <a href="<?=CFile::GetPath($item)?>" target="_blank" class="fancybox" data-fancybox-group="gallery">
                                                <img src="<?=$file['src']?>" alt="<?=$GLOBALS['PROPS']['B_H2_SLIDER']?>"/></a>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?></div>
                            <div class="slick-nav"><?
                                foreach($GLOBALS['PROPS']['B_SLIDER'] as $key=>$item)
                                {
                                    $file = CFile::ResizeImageGet($item, array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_EXACT, true);
                                    ?>
                                    <div>
                                        <div class="image">
                                                <img src="<?=$file['src']?>" alt="<?=$GLOBALS['PROPS']['B_H2_SLIDER']?>"/>
                                        </div>
                                    </div>
                                    <?
                                }
                                ?></div>
//----------component_epilog------------------------------------------------------------------------
<?use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/slick.min.js");
//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/jquery.fancybox.min.js");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick.css");
Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/slick-theme.css");
//Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/css/jquery.fancybox.min.css");
?>
<script>
$(document).ready(function() {

//$("a.fancybox").fancybox();
    });
</script>
<script>
    $(document).ready(function() {

//$("a.fancybox").fancybox();

        $('.slick-sl').slick({
            dots: false,
            infinite: true,
            speed: 300,
            fade:true,
            cssEase: 'linear',
            autoplay: true,
            autoplaySpeed: 2000,
            asNavFor: '.slick-nav',
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
        });

        $('.slick-nav').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.slick-sl',
            dots: true,
            centerMode: true,
            focusOnSelect: true
        });

    });
</script>
//-----css-----------------

.slick-sl
{
    width:400px;
    margin:20px auto;
}
.slick-nav
{
    width:400px;
    margin:20px auto;
    cursor: pointer;
}

.slick-dots li button.slick-next
{

}

.slick-dots li button.slick-prev
{

}

button.slick-prev:before,
button.slick-next:before
{
    color:#e31e25;
}

.slick-nav .image{
    padding-left: 5px;
}

.body ul.slick-dots li:before{
    display:none;
}

@media (max-width: 992px)
{
    .slick-sl
    {
        width:300px;
    }
    .slick-nav
    {
        width:300px;
    }
}

@media (max-width: 540px)
{
    .slick-sl
    {
        width:230px;
        margin:20px auto;
    }
    .slick-nav
    {
        width:230px;
        margin:20px auto;
    }
}