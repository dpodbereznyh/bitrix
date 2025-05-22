<?
if (!empty($arResult["PROPERTIES"]["LAYOUTS"]["VALUE"])) {
    $idsLayout = array();
    foreach ($arResult["PROPERTIES"]["LAYOUTS"]["VALUE"] as $layout) {
        $idsLayout[] = $layout;
    }
?>
    <div class="catalog-detail-layout-info">
        <? $APPLICATION->IncludeComponent(
            "forumedia:getlist",
            "detail_page",
            [
                'IBLOCK_ID' => $arResult["PROPERTIES"]["LAYOUTS"]["LINK_IBLOCK_ID"],
                'FILTER' => ['ACTIVE' => 'Y', 'ID' => $idsLayout],
                'SELECT' => [],
                'SORT' => ['ID' => $idsLayout],
            ],
            false
        ); ?>
    </div>
<?
}
?>
