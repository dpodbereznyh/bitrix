<? 2,13
CModule::IncludeModule("iblock");
$iblockId='';

$ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($iblockId);
$ipropIblockValues->clearValues();

$db_list = CIBlockSection::GetList([],['IBLOCK_ID'=>$iblockId],false,['IBLOCK_ID','ID']);
while($ar_result = $db_list->Fetch()){
$ipropSectionTemplates = new \Bitrix\Iblock\InheritedProperty\SectionTemplates($iblockId,$ar_result['ID']);
$ipropSectionTemplates->delete();

$ipropSectionValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($iblockId,$ar_result['ID']);
$ipropSectionValues->clearValues();
}

$db_list = CIBlockElement::GetList([],['IBLOCK_ID'=>$iblockId],false,false,['IBLOCK_ID','ID']);
while($ar_result = $db_list->Fetch()){
$ipropElementTemplates = new \Bitrix\Iblock\InheritedProperty\ElementTemplates($iblockId, $ar_result['ID']);
$ipropElementTemplates->delete();

$ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iblockId, $ar_result['ID']);
$ipropElementValues->clearValues();
}