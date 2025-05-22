<?
AddEventHandler("main", "OnPageStart", "OnPageStartHandler");

function OnPageStartHandler() {
    global $APPLICATION, $headerTransparent;


    $headerTransparent = 0;
    if (!CModule::IncludeModule("iblock")) {
        return;
    }
    $elemIblockId = 2;
    // Получаем чистый URL без "index.php"
    $requestUri = str_replace("index.php", "", $_SERVER["REQUEST_URI"]);
    $cleanedUrl = preg_replace('/\?.*/', '', $requestUri);
    // Разбиваем URL на части
    $arExplodeUrl = explode("/", $cleanedUrl);
    $filteredArray = array_values(array_filter($arExplodeUrl, function($value) {
        return !empty(trim($value)); // Учитываем только непустые строки
    }));
    // Получаем код элемента (последний элемент в массиве)
    $elementCode = end($filteredArray);
    $elementId = getElementIdByCode($elemIblockId, $elementCode);
    $res = CIBlockElement::GetProperty(2, 62, array(), Array("CODE"=>"HEADER_TRANSPARENT"));
    while ($ob = $res->GetNext())
    {
        $headerTransparent = $ob['VALUE'];
    }
}
function getElementIdByCode($iblockId, $elementCode) {
    $filter = array(
        'IBLOCK_ID' => $elemIblockId,
        'CODE' => $elementCode,
        'ACTIVE' => 'Y'
    );

    $element = CIBlockElement::GetList(array(), $filter, false, false, array('ID'));
    if ($ob = $element->GetNext()) {
        return $ob['ID'];
    }
    return null;
}
?>