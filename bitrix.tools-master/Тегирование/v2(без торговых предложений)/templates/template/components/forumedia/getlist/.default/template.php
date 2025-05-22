<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
//CJSCore::Init(array("jquery"));
if(!empty($arResult['ITEMS'])){
?>
<div class="teg-title">Часто ищут:</div>
<div class="teg-cont">
<?


if($arResult["SECTION_PAGE_URL"])
{

if(trim($arResult["SECTION_PAGE_URL"],'/')!=trim($APPLICATION->GetCurPage(),'/'))
    { ?><div class="teg" >
        <a href="<?=$arResult["SECTION_PAGE_URL"]?>"><?=$arResult["NAME"]?></a>
        </div>
        <?}
}


foreach($arResult['ITEMS'] as $item)
{ $is_active=(trim($item['PROPERTIES']['LINK']['~VALUE'],'/')==trim($APPLICATION->GetCurPage(),'/'));
    //var_dump(trim($item['PROPERTIES']['LINK'],'/'));
        //var_dump(trim($APPLICATION->GetCurPage(),'/'));
 ?><div class="teg <?=$is_active?'active':''?>" id="<?=$item['AREA_ID']?>">
     <a <?if(!$is_active){?>href="<?="/".trim($item['PROPERTIES']['LINK']['~VALUE'],"/")."/"?>"<?}?>
        class="<?=$is_active?'active':''?>"><div class="teg-in">
        <?=$item['PROPERTIES']['NAME']['~VALUE']?></div></a>
     <?if($is_active):?><a href="<?=$arParams["URL"]?>"><span class="go-sect">x</span></a><?endif?>
 </div><?   
}
?></div><?

}


