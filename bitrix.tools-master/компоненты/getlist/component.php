<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = array();

require_once 'WGet.php';

$cache = Bitrix\Main\Data\Cache::createInstance();
$taggedCache = Bitrix\Main\Application::getInstance()->getTaggedCache();
$cacheId = md5(serialize($arParams));
$cacheDir = "/menu_top";
$cacheTime = 3600 * 24;

if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {

    // отдаём данные из кеша
    $arResult = $cache->getVars();

} elseif ($cache->startDataCache()) {

    $taggedCache->StartTagCache($cacheDir);
    $taggedCache->RegisterTag('iblock_id_' . $arParams['IBLOCK_ID']);
    // выполняем код, чтобы положить данные в кэш
     $arResult = WGet::getResult(
        $arParams['IBLOCK_ID'],
        $arParams['FILTER'],
        !empty($arParams['SORT']) ? $arParams['SORT'] : array('sort' => 'ASC', 'id' => 'DESC'),
        !empty($arParams['NAV']) ? $arParams['NAV'] : false,
        !empty($arParams['SELECT']) ? $arParams['SELECT'] : [],
        !empty($arParams['IPROPS']) ? true : false);

    $taggedCache->EndTagCache();
    $cache->endDataCache($arResult);
}


//WGet::addLinks($arParams['IBLOCK_ID'], $arResult, false);

$this->IncludeComponentTemplate();

return $arResult;
