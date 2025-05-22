<?
/** [$arParams description]
 * 'IBLOCK_ID'
 * 'PAGEN_NAME' - название REQUEST переменной для пагинации, чтобы к meta добавлять номер страницы
 * 'CATALOG_PAGE' ='/catalog/' 
 * 'PAGE_TO_H1' = 'Y'
 * 'PAGE_TO_TITLE' = 'Y'
 * 'PAGE_TO_DESCRIPTION' = 'Y'
 */
 $APPLICATION->IncludeComponent(
    "forumedia:seo",
    "",
    Array('IBLOCK_ID'=>IBLOCK_REGIONS,
          'CACHE_TYPE'=>'N',
          'PAGEN_NAME'=>'PAGEN_1',
          'CATALOG_PAGE' =>'/catalog/',
          'PAGE_TO_H1' => 'Y',
          'PAGE_TO_TITLE' => 'Y',
          'PAGE_TO_DESCRIPTION' => 'Y'),
    false);
?>