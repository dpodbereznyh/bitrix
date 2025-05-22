<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * 'ELEMENT_CODE'
 * 'SECTION_CODE'
 * 'CATALOG_IBLOCK_ID' - инфоблок каталога
 * 'REPLACE'- ['#CODE#'=>VALUE] - шаблон и значение для замены
 * 'CATALOG_IBLOCK_ID'
 * 'TEXT_IBLOCK_ID' - текстовые записи секций
 * 'SEO_IBLOCK_ID' - seo текст инфоблок
 */


if(empty($arParams['TEXT_IBLOCK_ID'])){$arParams['TEXT_IBLOCK_ID']=IBLOCK_REGIONS_TEXT;}
if(empty($arParams['SEO_IBLOCK_ID'])){$arParams['SEO_IBLOCK_ID']=IBLOCK_REGIONS_SEO;}

$GLOBALS['CATALOG']['TYPE'] = (!empty($arParams['ELEMENT_CODE'])||!empty($arParams['ELEMENT_ID'])) ? 'E' : ((!empty($arParams['SECTION_CODE'])) ? 'S' : '');
$GLOBALS['CATALOG']['CODE'] = (!empty($arParams['ELEMENT_CODE'])) ? ($arParams['ELEMENT_CODE']) : ((!empty($arParams['SECTION_CODE'])) ?
    ($arParams['SECTION_CODE']) : '');
$GLOBALS['CATALOG']['ID']=$arParams['ELEMENT_ID'];
$GLOBALS['CATALOG']['IBLOCK_ID']     = $arParams['CATALOG_IBLOCK_ID'];
$GLOBALS['CATALOG']['SEO_IBLOCK_ID'] = $arParams['SEO_IBLOCK_ID'];
$GLOBALS['CATALOG']['TEXT_IBLOCK_ID'] = $arParams['TEXT_IBLOCK_ID'];
$GLOBALS['CATALOG']['REPLACE']       = $arParams['REPLACE'];


$handler = Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnEpilog", 'fseo');

function fseo()
{

    global $APPLICATION;
    global $CATALOG;

    $page = ($CATALOG['TYPE'] == 'E') ? 'catalog/element' : (($CATALOG['TYPE'] == 'S') ? 'catalog/section' : '');
    if (!empty($page)) {
        $rsRes = CIBlockElement::getList([],
            ['IBLOCK_ID'       => $GLOBALS['CATALOG']['SEO_IBLOCK_ID'],
             'PROPERTY_PAGE'    => trim($page, '/'),
             'PROPERTY_REGIONS' => $GLOBALS['REGIONS']['ACTIVE']['ID'],
             'ACTIVE'          => 'Y'],
            false,
            false,
            ['IBLOCK_ID', 'ID', 'NAME', 'PROPERTY_TITLE', 'PROPERTY_H1', 'PROPERTY_DESCRIPTION']);

        $arRes = $rsRes->Fetch();

        if ($CATALOG['TYPE'] == 'E') {
            $filter['IBLOCK_ID']= $CATALOG['IBLOCK_ID'];
            if(!empty($CATALOG['CODE']))$filter['CODE']= $CATALOG['CODE'];
            if(!empty($CATALOG['ID']))$filter['ID']= $CATALOG['ID'];
            $rs = CIBlockElement::getList([],
                $filter,
                false,
                false,
                ['IBLOCK_ID', 'ID', 'CODE', 'NAME']);
        }

        if ($CATALOG['TYPE'] == 'S') {
            $rs = CIBlockSection::getList([],
                ['IBLOCK_ID' => $CATALOG['IBLOCK_ID'], 'CODE' => $CATALOG['CODE']],
                false,
                ['IBLOCK_ID', 'ID', 'CODE', 'NAME']);
        }

        $params = [];
        if (!empty($rs)) {
            $params = $rs->Fetch();
        }

        if (!empty($arRes)) {

            $arRes['PROPERTY_H1_VALUE']          = str_replace('#NAME_LC#', mb_strtolower($params['NAME']), $arRes['PROPERTY_H1_VALUE']);
            $arRes['PROPERTY_TITLE_VALUE']       = str_replace('#NAME_LC#', mb_strtolower($params['NAME']), $arRes['PROPERTY_TITLE_VALUE']);
            $arRes['PROPERTY_DESCRIPTION_VALUE'] = str_replace('#NAME_LC#', mb_strtolower($params['NAME']), $arRes['PROPERTY_DESCRIPTION_VALUE']);
            $arRes['PROPERTY_H1_VALUE']          = str_replace('#NAME_UC#', mb_strtoupper($params['NAME']), $arRes['PROPERTY_H1_VALUE']);
            $arRes['PROPERTY_TITLE_VALUE']       = str_replace('#NAME_UC#', mb_strtoupper($params['NAME']), $arRes['PROPERTY_TITLE_VALUE']);
            $arRes['PROPERTY_DESCRIPTION_VALUE'] = str_replace('#NAME_UC#', mb_strtoupper($params['NAME']), $arRes['PROPERTY_DESCRIPTION_VALUE']);
            $arRes['PROPERTY_H1_VALUE']          = str_replace('#NAME#', $params['NAME'], $arRes['PROPERTY_H1_VALUE']);
            $arRes['PROPERTY_TITLE_VALUE']       = str_replace('#NAME#', $params['NAME'], $arRes['PROPERTY_TITLE_VALUE']);
            $arRes['PROPERTY_DESCRIPTION_VALUE'] = str_replace('#NAME#', $params['NAME'], $arRes['PROPERTY_DESCRIPTION_VALUE']);

            foreach ($GLOBALS['CATALOG']['REPLACE'] as $shabl => $value) {
                $shabl_lc                            = $shabl . '_LC';
                $shabl_uc                            = $shabl . '_UC';
                $arRes['PROPERTY_H1_VALUE']          = str_replace("#".$shabl_lc."#", mb_strtolower($value), $arRes['PROPERTY_H1_VALUE']);
                $arRes['PROPERTY_H1_VALUE']          = str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arRes['PROPERTY_H1_VALUE']);
                $arRes['PROPERTY_H1_VALUE']          = str_replace("#".$shabl."#", $value, $arRes['PROPERTY_H1_VALUE']);
                $arRes['PROPERTY_TITLE_VALUE']       = str_replace("#".$shabl_lc."#", mb_strtolower($value), $arRes['PROPERTY_TITLE_VALUE']);
                $arRes['PROPERTY_TITLE_VALUE']       = str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arRes['PROPERTY_TITLE_VALUE']);
                $arRes['PROPERTY_TITLE_VALUE']       = str_replace("#".$shabl."#", $value, $arRes['PROPERTY_TITLE_VALUE']);
                $arRes['PROPERTY_DESCRIPTION_VALUE'] = str_replace("#".$shabl_lc."#", mb_strtolower($value), $arRes['PROPERTY_DESCRIPTION_VALUE']);
                $arRes['PROPERTY_DESCRIPTION_VALUE'] = str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arRes['PROPERTY_DESCRIPTION_VALUE']);
                $arRes['PROPERTY_DESCRIPTION_VALUE'] = str_replace("#".$shabl."#", $value, $arRes['PROPERTY_DESCRIPTION_VALUE']);
            }

            if (!empty($arRes['PROPERTY_H1_VALUE'])) {$APPLICATION->SetTitle($arRes['PROPERTY_H1_VALUE']);}
            $APPLICATION->SetPageProperty("title", $arRes['PROPERTY_TITLE_VALUE']);
            $APPLICATION->SetPageProperty("description", $arRes['PROPERTY_DESCRIPTION_VALUE']);
        } else if ($GLOBALS['REGIONS']['ACTIVE']['DOMEN']['VALUE'] != FSEO_MAIN_DOMEN) {
            $APPLICATION->SetPageProperty("title", ' ');
            $APPLICATION->SetPageProperty("description", ' ');
        }

        if ($CATALOG['TYPE'] == 'S') {
            $rsRes = CIBlockElement::GetList(['SORT' => 'ASC'],
                [   'IBLOCK_ID'       => $CATALOG['TEXT_IBLOCK_ID'],
                    'PROPERTY_DIR'    => $params['ID'],
                    'PROPERTY_REGION' => $GLOBALS['REGIONS']['ACTIVE']['ID'],
                    'ACTIVE'          => 'Y'],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'PROPERTY_TITLE', 'PROPERTY_H1', 'PROPERTY_DESCRIPTION']);

            $elem = $rsRes->GetNext();

            if (!empty($elem['~PROPERTY_H1_VALUE'])) {

                $elem['~PROPERTY_H1_VALUE'] = str_replace('#NAME_LC#', mb_strtolower($params['NAME']), $elem['~PROPERTY_H1_VALUE']);
                $elem['~PROPERTY_H1_VALUE'] = str_replace('#NAME_UC#', mb_strtoupper($params['NAME']), $elem['~PROPERTY_H1_VALUE']);
                $elem['~PROPERTY_H1_VALUE'] = str_replace('#NAME#', $params['NAME'], $elem['~PROPERTY_H1_VALUE']);

                foreach ($GLOBALS['CATALOG']['REPLACE'] as $shabl => $value) {
                    $shabl_lc                   = $shabl . '_LC';
                    $shabl_uc                   = $shabl . '_UC';
                    $elem['~PROPERTY_H1_VALUE'] = str_replace("#".$shabl_lc."#", mb_strtolower($value), $arRes['~PROPERTY_H1_VALUE']);
                    $elem['~PROPERTY_H1_VALUE'] = str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arRes['~PROPERTY_H1_VALUE']);
                    $elem['~PROPERTY_H1_VALUE'] = str_replace("#".$shabl."#", $value, $arRes['~PROPERTY_H1_VALUE']);}
                $APPLICATION->SetTitle($elem['~PROPERTY_H1_VALUE']);
            }

           if (!empty($elem['~PROPERTY_TITLE_VALUE'])) {

                $elem['~PROPERTY_TITLE_VALUE'] = str_replace('#NAME_LC#', mb_strtolower($params['NAME']), $elem['~PROPERTY_TITLE_VALUE']);
                $elem['~PROPERTY_TITLE_VALUE'] = str_replace('#NAME_UC#', mb_strtoupper($params['NAME']), $elem['~PROPERTY_TITLE_VALUE']);
                $elem['~PROPERTY_TITLE_VALUE'] = str_replace('#NAME#', $params['NAME'], $elem['~PROPERTY_TITLE_VALUE']);

                foreach ($GLOBALS['CATALOG']['REPLACE'] as $shabl => $value) {
                    $shabl_lc                   = $shabl . '_LC';
                    $shabl_uc                   = $shabl . '_UC';
                    $elem['~PROPERTY_TITLE_VALUE'] = str_replace("#".$shabl_lc."#", mb_strtolower($value), $arRes['~PROPERTY_TITLE_VALUE']);
                    $elem['~PROPERTY_TITLE_VALUE'] = str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arRes['~PROPERTY_TITLE_VALUE']);
                    $elem['~PROPERTY_TITLE_VALUE'] = str_replace("#".$shabl."#", $value, $arRes['~PROPERTY_TITLE_VALUE']);}
                $APPLICATION->SetPageProperty("title",$elem['~PROPERTY_TITLE_VALUE']);
            }

             if (!empty($elem['~PROPERTY_DESCRIPTION_VALUE'])) {

                $elem['~PROPERTY_DESCRIPTION_VALUE'] = str_replace('#NAME_LC#', mb_strtolower($params['NAME']), $elem['~PROPERTY_DESCRIPTION_VALUE']);
                $elem['~PROPERTY_DESCRIPTION_VALUE'] = str_replace('#NAME_UC#', mb_strtoupper($params['NAME']), $elem['~PROPERTY_DESCRIPTION_VALUE']);
                $elem['~PROPERTY_DESCRIPTION_VALUE'] = str_replace('#NAME#', $params['NAME'], $elem['~PROPERTY_DESCRIPTION_VALUE']);

                foreach ($GLOBALS['CATALOG']['REPLACE'] as $shabl => $value) {
                    $shabl_lc                   = $shabl . '_LC';
                    $shabl_uc                   = $shabl . '_UC';
                    $elem['~PROPERTY_DESCRIPTION_VALUE'] = str_replace("#".$shabl_lc."#", mb_strtolower($value), $arRes['~PROPERTY_DESCRIPTION_VALUE']);
                    $elem['~PROPERTY_DESCRIPTION_VALUE'] = str_replace("#".$shabl_uc."#", mb_strtoupper($value), $arRes['~PROPERTY_DESCRIPTION_VALUE']);
                    $elem['~PROPERTY_DESCRIPTION_VALUE'] = str_replace("#".$shabl."#", $value, $arRes['~PROPERTY_DESCRIPTION_VALUE']);}
                $APPLICATION->SetPageProperty("description",$elem['~PROPERTY_DESCRIPTION_VALUE']);
            }
        }
    }
}

$this->IncludeComponentTemplate();

return $arResult;
