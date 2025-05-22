<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$db_list = CIBlockSection::GetList(
    [],
    ['IBLOCK_ID'=>13,'ID'=>$arParams['FILTER']['PROPERTY_SECTIONS']],
    false,
    ['IBLOCK_ID','ID','SECTION_PAGE_URL','NAME']);

$sect=$db_list->GetNext();

$arResult["SECTION_PAGE_URL"]=$sect["SECTION_PAGE_URL"];
$arResult["NAME"]=$sect["NAME"];