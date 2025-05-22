<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = array();

\Bitrix\Main\Loader::includeModule('iblock');

if (!empty($arParams['SITY'])) {

    if (!empty($arParams['QUERY'])) {
        $arRes = CIBlockElement::GetList(['sort' => 'ASC', 'name' => 'ASC'],
            ['IBLOCK_ID' => IBLOCK_REGIONS, 'NAME' => $arParams['QUERY'] . '%', '!NAME' => $GLOBALS['REGIONS']['ACTIVE']['NAME'], 'ACTIVE' => 'Y'],
            false,
            false,
            ['ID', 'IBLOCK_ID', 'CODE', 'NAME', 'PROPERTY_DOMEN']
        );
        while ($el = $arRes->Fetch()) {
            $arResult['OPTION'][] = $el;
        }
    }

    $arRes = CIBlockElement::GetList(['sort' => 'ASC', 'name' => 'ASC'],
        ['IBLOCK_ID' => IBLOCK_REGIONS, 'PROPERTY_VIEW_VALUE' => 'Y', '!NAME' => $GLOBALS['REGIONS']['ACTIVE']['NAME'], 'ACTIVE' => 'Y'],
        false,
        false,
        ['ID', 'IBLOCK_ID', 'CODE', 'NAME', 'PROPERTY_DOMEN']
    );

    while ($el = $arRes->Fetch()) {
        $arResult['VIEW'][] = $el;
    }
}
$this->IncludeComponentTemplate();

return $arResult;
