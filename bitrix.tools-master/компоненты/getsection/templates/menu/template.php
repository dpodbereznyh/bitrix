<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));
if (!empty($arResult[0]['ITEMS'])) {

    function recMenu($items)
    {
        ?><div class="top-menu-catalog level-<?=$items[0]['DEPTH_LEVEL']?>">
            <?
if($items[0]['DEPTH_LEVEL']):?>
<div class="napravl_last">&#8249;</div>
    <?endif;
        foreach ($items as $item) {
            ?><div class="item-catalog"><?
            if ($item['DEPTH_LEVEL'] == '1') {
                $img = CFile::ResizeImageGet($item['PICTURE'], array("width" => 40, "height" => 40), BX_RESIZE_IMAGE_PROPORTIONAL);
                ?><div class="item-link">
                <?if (!empty($img['src'])): ?><img src="<?=$img['src']?>"/><?endif?>
    <a href="<?=$item['~SECTION_PAGE_URL']?>">
    <?=$item['~NAME']?>
    </a></div>
        <?
            } else {
                ?>
    <a href="<?=$item['~SECTION_PAGE_URL']?>">
    <span class="menu-text"><?=$item['~NAME']?></span>

    </a>
        <?}?>
        <?if (!empty($item['ITEMS'])) 
        { ?> <span class="napravl">&#8250;</span>
            <?
                recMenu($item['ITEMS']);
            }
            ?>
</div>
        <?

        }?>
        </div><?
    }
?><div class="top-menu-catalog-head"><?
    recMenu($arResult[0]['ITEMS']);
?></div><?
}
