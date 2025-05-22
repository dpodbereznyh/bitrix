//вывод тегов
<?
if(!empty($arResult["VARIABLES"]["FILTER"]))
    {$GLOBALS[$arParams['FILTER_NAME']]=$arResult["VARIABLES"]["FILTER"];}?>
 
   <div class="filter_horizontal">
			<?if($arResult['VARIABLES']['PAGE']=='teg'){
	$filterTeg=['ACTIVE' => 'Y', 'ID' => $arResult["VARIABLES"]["TEGS"]];
	$isteg=true;
}else
 			{$filterTeg=['ACTIVE' => 'Y', 'PROPERTY_SECTIONS' => $arResult["VARIABLES"]["SECTION_ID"]];

				$isteg=false;
 		}?>

 <?$APPLICATION->IncludeComponent(
    "forumedia:catalog.teg.list",
    "",
    [	'SORT'=>array('sort' => 'ASC', 'id' => 'ASC'),
        'IBLOCK_ID' => $arParams['IBLOCK_TEGS'],
        'FILTER'    => $filterTeg,
        'IS_TEG'=>$isteg,
        'URL'=>$arResult["VARIABLES"]["TEG_SECTION_URL"],
        'CATALOG_IBLOCK_ID'=>$arParams['IBLOCK_ID'],
        'CATALOG_SECTION_ID' => $arResult["VARIABLES"]["SECTION_ID"]
    ],
    false
);?></div>

/**
 * каталог
 */

// вывод текста
<? if($arResult['VARIABLES']['PAGE']=='teg'): ?>
						<? if(!empty($arResult['VARIABLES']["TEG_TEXT_BOTTOM"])): ?>
						<?=$arResult['VARIABLES']["TEG_TEXT_BOTTOM"];?>
						<?endif;?>
					<?else:?>