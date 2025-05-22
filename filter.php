<?
$tags = [];//список всех тегов
$arSelect = Array("ID", "IBLOCK_ID", "PROPERTY_TAGS");
$arFilter = Array("IBLOCK_ID"=>ID_ИНФОБЛОКА_НОВОСТЕЙ, "ACTIVE"=>"Y", "!PROPERTY_TAGS" => false);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($arFields = $res->GetNext()){
  if(!in_array($arFields['PROPERTY_TAGS_VALUE'], $tags)){
     $tags[] = $arFields['PROPERTY_TAGS_VALUE'];
  }
  
}

//Фильтр по тегам
if(isset($_GET['tag']) && !empty($_GET['tag'])){
  $GLOBALS['arrFilter'] = ['PROPERTY_TAGS' => $_GET['tag']];
}



$APPLICATION->IncludeComponent("bitrix:news","",Array(
    ...
    "USE_FILTER" => "Y",
    "FILTER_NAME" => "arrFilter",
    ...
    )
);
?>