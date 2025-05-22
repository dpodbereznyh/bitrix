<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * 'ELEMENT_CODE'
 * 'SECTION_CODE'
 * 'REPLACE'- ['#CODE#'=>VALUE] - шаблон и значение для замены
 * 'CATALOG_IBLOCK_ID' - инфоблок каталога
 * 'TEXT_IBLOCK_ID' - текстовые записи секций
 * 'SEO_IBLOCK_ID' - seo текст инфоблок
 */


 $APPLICATION->IncludeComponent(
    "forumedia:seo.catalog.epilog",
    "",
    Array('CATALOG_IBLOCK_ID'=>$arParams['IBLOCK_ID'],
          'CACHE_TYPE'=>'N',
          'ELEMENT_CODE'=> $arResult['VARIABLES']['ELEMENT_CODE'],
          'ELEMENT_ID'=> $arResult['VARIABLES']['ELEMENT_ID'],
          'SECTION_CODE'=> $arResult['VARIABLES']['SECTION_CODE']
          ),
    false);
