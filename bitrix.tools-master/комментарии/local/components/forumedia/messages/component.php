<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array();

require_once 'WMessages.php';
if(isset($arParams['PROPS']))
{
    WMessages::update($arParams['ID'], $arParams['IBLOCK_ID'], $arParams['PROPS']);
}

$arResult['ITEMS']=WMessages::getResult(
    $arParams['IBLOCK_ID'],
    $arParams['ID'],
    isset($arParams['SORT'])?$arParams['SORT']:Array("SORT"=>"ASC"),
    isset($arParams['SELECT'])?$arParams['SELECT']:[],
    isset($arParams['PAGE'])?$arParams['PAGE']:[]);

	WMessages::addLinks($arParams['IBLOCK_ID'],$arResult['ITEMS']);

if(!empty($arParams['PAGE'])){
    $arResult['COUNT']=CIBlockElement::GetList([],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID'],
     'PROPERTY_CATALOG' => $arParams['ID'],
     'ACTIVE'=>'Y'],[]);}

$this->IncludeComponentTemplate();

return $arResult;
?>


