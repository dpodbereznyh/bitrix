<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<div class="main-sect">
    <?

foreach($arResult as $item){
$img=CFile::ResizeImageGet($item['PICTURE'],Array("width"=>273, "height"=>171),BX_RESIZE_IMAGE_EXACT);
$name=$item['NAME'];
$url=$item['SECTION_PAGE_URL'];
$page_alt=$item['NAME'];
?>
<a href="<?=$url?>">

<div class="main-sect-item" id="<?=$item['AREA_ID']?>">
    <img src="<?=$img['src']?>" alt="<?=$page_alt?>">
<div class="main-sect-item_name"><span><?=$name?></span></div>
</div>

</a>

<?
}

?></div><?