<?php
// Получаем ID раздела
$sectionId = $arResult['ID']; // Убедитесь, что это ID нужного вам раздела

// Получаем ID инфоблока
$iblockId = $arResult['IBLOCK_ID']; // Убедитесь, что у вас есть ID инфоблока

// Компилируем сущность для разделов инфоблока
$entity = \Bitrix\Iblock\Model\Section::compileEntityByIblock($iblockId);

// Получаем данные о разделе по его ID
$rsSection = $entity::getList(array(
    "filter" => array(
        "ID" => $sectionId, // Фильтруем по ID раздела
        "ACTIVE" => "Y", // Убедитесь, что раздел активен
    ),
    "select" => array("ID", "UF_SECTION_BANNER_DESC", "UF_SECTION_BANNER_LINK"), // Выбираем нужные поля
));

if ($arSection = $rsSection->fetch()) {
    // Сохраняем значения пользовательских свойств в переменные
    $bannerDesc = isset($arSection['UF_SECTION_BANNER_DESC']) ? $arSection['UF_SECTION_BANNER_DESC'] : null;
    $bannerLink = isset($arSection['UF_SECTION_BANNER_LINK']) ? $arSection['UF_SECTION_BANNER_LINK'] : null;

    // Теперь вы можете использовать $bannerDesc и $bannerLink в дальнейшем
} else {
    // Обработка случая, когда раздел не найден или не активен
    $bannerDesc = null;
    $bannerLink = null;
}
?>