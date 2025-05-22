<?require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

/**
 *
 * Добавить
 * ?action=ADD_TO_COMPARE_LIST&id=96
 *
 * Удалить
 * ?action=DELETE_FROM_COMPARE_LIST&id=96
 *
 */

$_REQUEST["ajax_action"] = "Y";

$APPLICATION->IncludeComponent(
    "bitrix:catalog.compare.list",
    "ajax",
    Array(
        "ACTION_VARIABLE" => "action",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "COMPARE_URL" => "/catalog/compare/",
        "COMPONENT_TEMPLATE" => ".default",
        "DETAIL_URL" => "",
        "IBLOCK_ID" => '11',
        "IBLOCK_TYPE" => "aspro_kshop_catalog",
        "NAME" => 'CATALOG_COMPARE_LIST',
        "POSITION" => "top left",
        "POSITION_FIXED" => "Y",
        "PRODUCT_ID_VARIABLE" => "id"
    )
);
?>