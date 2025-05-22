<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * [$arParams description]
 * 'CATALOG_IBLOCK_ID'
 * 'SECTION_ID' - обязательный параметр
 * 'SECTION_CODE'
 * 'PROPERTY_CODE' - код свойства который выводим на странице
 * 'REPLACE'- ['#CODE#'=>VALUE] - шаблон и значение для замены
 * 'PAGEN_NAME' - если пусто, то на каждой странице выводится, если имя REQUEST, то только на главной.
 * 'TEXT_IBLOCK_ID' - текстовые записи секций
 * 'TEXT_MAIN_DOMAIN' - подменяется этим текстом при отсутствии в инфоблоке на главном домене
 */

 $APPLICATION->IncludeComponent(
    "forumedia:seo.catalog.text",
    "",
    Array('CATALOG_IBLOCK_ID'=>$arParams['IBLOCK_ID'],
          'CACHE_TYPE'=>'N',
          'SECTION_CODE'=> $arResult['VARIABLES']['SECTION_CODE'],
          'SECTION_ID'=> $arResult['VARIABLES']['SECTION_ID'],
          'PROPERTY_CODE'=>"DESCRIPTION_TOP",
          'PAGEN_NAME'=>'PAGEN_1',
          'TEXT_MAIN_DOMAIN'=>''
          ),
    false);