<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
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
 * 'TEXT_IBLOCK_ID' - инфоблок сео текстовые записи секций
 * 'TEXT_MAIN_DOMAIN'
 */
$arResult = array();

if(empty($arParams['TEXT_IBLOCK_ID'])){$arParams['TEXT_IBLOCK_ID']=IBLOCK_REGIONS_TEXT;}

\Bitrix\Main\Loader::includeModule('iblock');

$arResult['TEXT']='';

if (!empty($arParams['SECTION_ID'])) {
    $rsRes = CIBlockElement::GetList(['SORT' => 'ASC'],
        ['IBLOCK_ID'      => $arParams['TEXT_IBLOCK_ID'],
        'PROPERTY_DIR'    => $arParams['SECTION_ID'],
        'PROPERTY_REGION' => $GLOBALS['REGIONS']['ACTIVE']['ID'],
        'ACTIVE'          => 'Y'],
        false,
        false,
        ['IBLOCK_ID', 'ID', 'PROPERTY_'.$arParams['PROPERTY_CODE']]);
    $elems = $rsRes->GetNext();
    $sfilter=['IBLOCK_ID' => $arParams['CATALOG_IBLOCK_ID']];

    if(!empty($arParams['SECTION_CODE']))
        { $sfilter['CODE']=$arParams['SECTION_CODE'];}
    elseif(!empty($arParams['SECTION_ID']))
        { $sfilter['ID']=$arParams['SECTION_ID'];}

    $rs = CIBlockSection::getList([],
        $sfilter,
        false,
        ['IBLOCK_ID', 'ID', 'CODE', 'NAME']);
    $elName=$rs->Fetch();

    add_links($arParams['TEXT_IBLOCK_ID'], $elems, new CBitrixComponent());

    $arResult = $elems;

    $region=$GLOBALS['REGIONS']['ACTIVE']['NAME'];
    $region_pp=$GLOBALS['REGIONS']['ACTIVE']['REGION_PP']['VALUE'];
    $region_tp=$GLOBALS['REGIONS']['ACTIVE']['REGION_TP']['VALUE'];
    $region_rp=$GLOBALS['REGIONS']['ACTIVE']['REGION_RP']['VALUE'];

    $arResult['TEXT']=(!empty($arResult['~PROPERTY_'.$arParams['PROPERTY_CODE'].'_VALUE']['TEXT']))?
                            $arResult['~PROPERTY_'.$arParams['PROPERTY_CODE'].'_VALUE']['TEXT']:
                                $arResult['~PROPERTY_'.$arParams['PROPERTY_CODE'].'_VALUE'];

    $arResult['TEXT']=str_replace('#NAME_LC#', mb_strtolower($elName['NAME']), $arResult['TEXT']);
    $arResult['TEXT']=str_replace('#NAME_UC#', mb_strtoupper($elName['NAME']), $arResult['TEXT']);
    $arResult['TEXT']=str_replace('#NAME#', $elName['NAME'], $arResult['TEXT']);

    $arResult['TEXT']=str_replace('#REGION_PP_LC#',mb_strtolower($region_pp),$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_TP_LC#',mb_strtolower($region_tp),$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_RP_LC#',mb_strtolower($region_rp),$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_LC#',mb_strtolower($region),$arResult['TEXT']);

    $arResult['TEXT']=str_replace('#REGION_PP_UC#',mb_strtoupper($region_pp),$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_TP_UC#',mb_strtoupper($region_tp),$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_RP_UC#',mb_strtoupper($region_rp),$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_UC#',mb_strtoupper($region),$arResult['TEXT']);

    $arResult['TEXT']=str_replace('#REGION_PP#',$region_pp,$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_TP#',$region_tp,$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION_RP#',$region_rp,$arResult['TEXT']);
    $arResult['TEXT']=str_replace('#REGION#',$region,$arResult['TEXT']);

    foreach($arParams['REPLACE'] as $shabl=>$value)
    {
        $shabl_lc=$shabl.'_LC';
        $shabl_uc=$shabl.'_UC';
        $arResult['TEXT']=str_replace("#".$shabl_lc."#", mb_strtolower($value), $arResult['TEXT']);
        $arResult['TEXT']=str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arResult['TEXT']);
        $arResult['TEXT']=str_replace("#".$shabl."#", $value, $arResult['TEXT']);
    }

}

if(isMainDomain()){
    if(empty($arResult['TEXT']))
        {$arResult['TEXT']=htmlspecialchars_decode($arParams['TEXT_MAIN_DOMAIN']);}
}

if(!empty($arParams['PAGEN_NAME'])&&!empty($_REQUEST[$arParams['PAGEN_NAME']])&&($_REQUEST[$arParams['PAGEN_NAME']]!=1)){
    $arResult['TEXT']='';
}

$this->IncludeComponentTemplate();

return $arResult;
