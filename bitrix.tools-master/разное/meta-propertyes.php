<?
$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(39, $arParams["SECTION_ID"]);
$values = $ipropValues->getValues();
//выводим наши мета-данные
if($values['SECTION_META_TITLE']){
    $APPLICATION->SetPageProperty("title", $values['SECTION_META_TITLE']);
}
if($values['SECTION_META_DESCRIPTION']){
    $APPLICATION->SetPageProperty("description", $values['SECTION_META_DESCRIPTION']);
}
if($values['SECTION_META_KEYWORDS']){
    $APPLICATION->SetPageProperty("keywords", $values['SECTION_META_KEYWORDS']);
}

$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(39, $arParams["ELEMENT_ID"]);
$values = $ipropValues->getValues();
//выводим наши мета-данные
if($values['ELEMENT_META_TITLE']){
    $APPLICATION->SetPageProperty("title", $values['ELEMENT_META_TITLE']);
}
if($values['ELEMENT_META_DESCRIPTION']){
    $APPLICATION->SetPageProperty("description", $values['ELEMENT_META_DESCRIPTION']);
}
if($values['ELEMENT_META_KEYWORDS']){
    $APPLICATION->SetPageProperty("keywords", $values['ELEMENT_META_KEYWORDS']);
}