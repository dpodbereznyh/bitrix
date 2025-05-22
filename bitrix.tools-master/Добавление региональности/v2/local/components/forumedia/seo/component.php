<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * [$arParams description]
 * 'IBLOCK_ID'
 * 'PAGEN_NAME' - название REQUEST переменной для пагинации, чтобы к meta добавлять номер страницы
 * 'CATALOG_PAGE' ='/catalog/' 
 * 'PAGE_TO_H1' = 'Y'  - добавлять пагинацию
 * 'PAGE_TO_TITLE' = 'Y'
 * 'PAGE_TO_DESCRIPTION' = 'Y'
 */
$arResult = array();

\Bitrix\Main\Loader::includeModule('iblock');

if(empty($arParams['CATALOG_PAGE']))
{
    $arParams['CATALOG_PAGE']='/catalog/';
}

if(empty($arParams['PAGE_TO_H1'])){
    $arParams['PAGE_TO_H1']='Y';
}

if(empty($arParams['PAGE_TO_TITLE'])){
    $arParams['PAGE_TO_TITLE']='Y';
}

if(empty($arParams['PAGE_TO_DESCRIPTION'])){
    $arParams['PAGE_TO_DESCRIPTION']='Y';
}

if(!function_exists('fregion')){

function fregion(){
global $APPLICATION;
    
$page=$APPLICATION->GetCurPage(false);
$rsRes=CIBlockElement::getList([],
['IBLOCK_ID'=>IBLOCK_REGIONS_SEO,'PROPERTY_PAGE'=>($page!='/')?trim($page,'/'):'/','PROPERTY_REGIONS'=>$GLOBALS['REGIONS']['ACTIVE']['ID']],
false,
false,
['IBLOCK_ID','ID','NAME','PROPERTY_TITLE','PROPERTY_H1','PROPERTY_DESCRIPTION']);

$arRes=$rsRes->Fetch();
if(empty($GLOBALS['CATALOG']['CODE']))
    {
if(!empty($arRes))
{
if(!empty($arRes['PROPERTY_H1_VALUE'])){$APPLICATION->SetTitle($arRes['PROPERTY_H1_VALUE']);}
$APPLICATION->SetPageProperty("title",$arRes['PROPERTY_TITLE_VALUE']);
$APPLICATION->SetPageProperty("description",$arRes['PROPERTY_DESCRIPTION_VALUE']);
}
else if($GLOBALS['REGIONS']['ACTIVE']['DOMEN']['VALUE']!=FSEO_MAIN_DOMEN)
{
$APPLICATION->SetPageProperty("title",' ');
$APPLICATION->SetPageProperty("description",' ');
}
    }else{
    if(!empty($arRes['PROPERTY_H1_VALUE'])){$APPLICATION->SetTitle($arRes['PROPERTY_H1_VALUE']);}
    if(!empty($arRes['PROPERTY_TITLE_VALUE'])){$APPLICATION->SetPageProperty("title",$arRes['PROPERTY_TITLE_VALUE']);}
    if(!empty($arRes['PROPERTY_DESCRIPTION_VALUE'])){$APPLICATION->SetPageProperty("description",$arRes['PROPERTY_DESCRIPTION_VALUE']);}
    }
    //}

$region=$GLOBALS['REGIONS']['ACTIVE']['NAME'];
$region_pp=$GLOBALS['REGIONS']['ACTIVE']['REGION_PP']['VALUE'];
$region_tp=$GLOBALS['REGIONS']['ACTIVE']['REGION_TP']['VALUE'];
$region_rp=$GLOBALS['REGIONS']['ACTIVE']['REGION_RP']['VALUE'];

    $title=$APPLICATION->GetPageProperty("title");
$title=str_replace('#REGION_PP#',$region_pp,$title);
$title=str_replace('#REGION_TP#',$region_tp,$title);
$title=str_replace('#REGION_RP#',$region_rp,$title);
$title=str_replace('#REGION#',$region,$title);

if(!empty($_REQUEST[$arParams['PAGEN_NAME']])&&$arParams['PAGE_TO_TITLE']=='Y'&&
($_REQUEST[$arParams['PAGEN_NAME']]>1)&&
(!empty($title))&&
(strpos($title,'| Страница')===false))
{
$title.= ' | Страница '.$_REQUEST[$arParams['PAGEN_NAME']];
}
    $APPLICATION->SetPageProperty("title",$title);

    $description=$APPLICATION->GetPageProperty("description");
$description=str_replace('#REGION_PP#',$region_pp,$description);
$description=str_replace('#REGION_TP#',$region_tp,$description);
$description=str_replace('#REGION_RP#',$region_rp,$description);
$description=str_replace('#REGION#',$region,$description);

if(!empty($arParams['PAGEN_NAME'])&&$arParams['PAGE_TO_DESCRIPTION']=='Y'&&
    !empty($_REQUEST[$arParams['PAGEN_NAME']])&&
        ($_REQUEST[$arParams['PAGEN_NAME']]>1)&&
         (!empty($description))&&
            (strpos($description,'| Страница')===false))
{
$description.= ' | Страница '.$_REQUEST[$arParams['PAGEN_NAME']];
}

    $APPLICATION->SetPageProperty("description",$description);

    $h1=$APPLICATION->GetTitle();
$h1=str_replace('#REGION_PP#',$region_pp,$h1);
$h1=str_replace('#REGION_TP#',$region_tp,$h1);
$h1=str_replace('#REGION_RP#',$region_rp,$h1);
$h1=str_replace('#REGION#',$region,$h1);

    if(!empty($arParams['PAGEN_NAME'])&&$arParams['PAGE_TO_H1']=='Y'&&
        strpos($APPLICATION->GetCurPage(false),$arParams['CATALOG_PAGE'])===false && 
        !empty($_REQUEST[$arParams['PAGEN_NAME']])&&
($_REQUEST[$arParams['PAGEN_NAME']]>1)&&
(!empty($h1))&&
(strpos($h1,'| Страница')===false))
{
$h1.= ' | Страница '.$_REQUEST[$arParams['PAGEN_NAME']];
}
    $APPLICATION->SetTitle($h1);

}

$handler = Bitrix\Main\EventManager::getInstance()->addEventHandler("main","OnEpilog",'fregion');}

$this->IncludeComponentTemplate();

return $arResult;
