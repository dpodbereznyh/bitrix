<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$scRes = CIBlockSection::GetNavChain(
    $arParams['IBLOCK_ID'],
    $arResult['IBLOCK_SECTION_ID'],
    array("ID","DEPTH_LEVEL",'NAME')
);
$ROOT_SECTION_NAME = 0;
while($arGrp = $scRes->Fetch()){
    // определяем корневой раздел
    if ($arGrp['DEPTH_LEVEL'] == 1){
        $ROOT_SECTION_NAME = $arGrp['NAME'];
    }
}

if($ROOT_SECTION_NAME == 'Женщинам'){
    $pol = 'женские';
    $polUP = 'Женские';
}
else{
    $pol = 'мужские';
    $polUP = 'Мужские';
}

$filter = $GLOBALS['arrFilter'];
unset($filter['PROPERTY_OSNOVNOY_TOVAR_VALUE']);
unset($filter['FACET_OPTIONS']);
foreach($filter as $key=>$val) {

    /* получаем информацию по id значения свойств */
    $property_enums = CIBlockPropertyEnum::GetList(
        ["DEF" => "DESC", "SORT" => "ASC"],
        ["IBLOCK_ID" => 1, "ID" => $val]
    );

    while ($enum_fields = $property_enums->GetNext()) {
        $arrMetaFilter[$enum_fields["PROPERTY_CODE"]]["name_prop"] = $enum_fields["PROPERTY_NAME"];
        $arrMetaFilter[$enum_fields["PROPERTY_CODE"]]["value"][] = $enum_fields["VALUE"];
    }
}

foreach ($arrMetaFilter as $fil){
   $arrRes[] = array(
        0 => mb_strtolower($fil['name_prop']).': ',
        1 => implode(', ',$fil['value']),
   );
}
$str = '';
foreach ($arrRes as $item){
    $str .= $item[0].$item[1].'; ';
}

if(!empty($str)){
    $str = ', ' .$str;
}

?>
<?php

$arResult['POL'] = $pol;
$arResult['POL_UP'] = $polUP;
$arResult['FILTER'] = $str;
$cp = $this->__component;
if (is_object($cp))
    $cp->SetResultCacheKeys(array('POL','POL_UP','NAME','SECTION_PAGE_URL','FILTER'));
?>



