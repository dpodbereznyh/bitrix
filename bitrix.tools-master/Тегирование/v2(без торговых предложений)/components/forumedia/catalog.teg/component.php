<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @global CMain $APPLICATION */
if (isset($arParams["USE_FILTER"]) && $arParams["USE_FILTER"] == "Y") {
    $arParams["FILTER_NAME"] = trim($arParams["FILTER_NAME"]);
    if ($arParams["FILTER_NAME"] === '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
        $arParams["FILTER_NAME"] = "arrFilter";
    }

} else {
    $arParams["FILTER_NAME"] = "";
}

//default gifts
if (empty($arParams['USE_GIFTS_SECTION'])) {
    $arParams['USE_GIFTS_SECTION'] = 'Y';
}
if (empty($arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'])) {
    $arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'] = 3;
}
if (empty($arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'])) {
    $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'] = 4;
}
if (empty($arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'])) {
    $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'] = 4;
}

$arParams['ACTION_VARIABLE'] = (isset($arParams['ACTION_VARIABLE']) ? trim($arParams['ACTION_VARIABLE']) : 'action');
if ($arParams["ACTION_VARIABLE"] == '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"])) {
    $arParams["ACTION_VARIABLE"] = "action";
}

$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"] ? $arParams["SEF_URL_TEMPLATES"]["section"] : "#SECTION_ID#/");
$arDefaultUrlTemplates404 = array(
    "sections" => "",
    "section" => "#SECTION_ID#/",
    "element" => "#SECTION_ID#/#ELEMENT_ID#/",
    "compare" => "compare.php?action=COMPARE",
    "smart_filter" => $smartBase . "filter/#SMART_FILTER_PATH#/apply/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
    "SECTION_ID",
    "SECTION_CODE",
    "ELEMENT_ID",
    "ELEMENT_CODE",
    "action",
);

if ($arParams["SEF_MODE"] == "Y") {
    $arVariables = array();

    $engine = new CComponentEngine($this);
    if (\Bitrix\Main\Loader::includeModule('iblock')) {
        $engine->addGreedyPart("#SECTION_CODE_PATH#");
        $engine->addGreedyPart("#SMART_FILTER_PATH#");
        $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
    }

    $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
    $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

    $componentPage = $engine->guessComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );

    $urlElems = explode('/', trim($APPLICATION->GetCurPage(), '/'));
    $endUrl = array_pop($urlElems);

    if(true){

        //находим страницу с готовым тегированием
        $cpage1 = '/' . trim($APPLICATION->GetCurPage(), '/') . '/';
        $cpage2 = '/' . trim($APPLICATION->GetCurPage(), '/');

        $res = CIBlockElement::GetList(array(),
            ['ACTIVE' => 'Y',
                'IBLOCK_ID' => $arParams['IBLOCK_TEGS'],
                ['LOGIC' => 'OR', ['=PROPERTY_LINK' => $cpage1], ['=PROPERTY_LINK' => $cpage2]]]);

        $arFields = [];
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arFields['PROPERTIES'] = $ob->GetProperties();
        }

        function getChainToTeg(&$chain, $id, $iblock, &$ids)
        {
            $res = CIBlockElement::GetList(
                array(),
                ['ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock,
                    'PROPERTY_TEGS' => $id], false, false, ['ID', 'PROPERTY_H1', 'PROPERTY_LINK', 'PROPERTY_SECTIONS']);
            $ob = $res->GetNext();
            if (in_array($ob['ID'], $ids)) {return false;}
            if (!empty($ob)) {
                $ids[] = $ob['ID'];
                if (!getChainToTeg($chain, $ob['ID'], $iblock, $ids)) {
                    return false;
                }
                ;
            }

            if (!empty($ob['PROPERTY_H1_VALUE'])) {
                $chain[] = ['section' => $ob['PROPERTY_SECTIONS_VALUE'], 'path' => $ob['PROPERTY_LINK_VALUE'], 'name' => (isset($ob['~PROPERTY_H1_VALUE']['TEXT'])) ? $ob['~PROPERTY_H1_VALUE']['TEXT'] : $ob['~PROPERTY_H1_VALUE']];
            }

            return true;}

        function getChainToTegShablon(&$chain, $id, $iblock, &$ids, &$codes, $LAST_SECTION_ID)
        {
            if (!empty($codes)) {$code = array_shift($codes);} else {return true;}

            $res = CIBlockElement::GetList(
                array(),
                ['ACTIVE' => 'Y',
                    'IBLOCK_ID' => $iblock,
                    //'!PROPERTY_PATTERN'=>false,
                    'PROPERTY_TEGS' => $id], false, false, ['IBLOCK_ID', 'ID']);
            $ob = [];
            $obTemp = $res->GetNextElement();
            if ($obTemp) {
                $ob = $obTemp->GetProperties();
            }

            if (empty($ob['PATTERN']['VALUE']) && !empty($code)) {

                $firstfind = false;
                if (strpos($ob['LINK']['VALUE'], '/' . $code . '/') !== false) {
                    if (empty($codes) && in_array($LAST_SECTION_ID, $ob['SECTIONS']['VALUE'])) {
                        $firstfind = true;
                    }
                    if (!empty($codes)) {
                        $firstfind = true;
                    }
                }

                if (!$firstfind) {
                    while ($obTemp = $res->GetNextElement()) {
                        $ob = $obTemp->GetProperties();

                        if (strpos($ob['LINK']['VALUE'], '/' . $code . '/') !== false) {
                            if (empty($codes) && in_array($LAST_SECTION_ID, $ob['SECTIONS']['VALUE'])) {

                                break;
                            }
                            if (!empty($codes)) {

                                break;
                            }
                        }

                    }}

            }
            if (in_array($ob['ID'], $ids)) {return false;}
            if (!empty($ob) && !empty($code)) {
                $ids[] = $ob['ID'];
                if (!getChainToTegShablon($chain, $ob['ID'], $iblock, $ids, $codes, $LAST_SECTION_ID)) {
                    return false;
                }
                ;
            }

            if (!empty($ob['H1']['VALUE'])) {
                $chain[] = ['section' => $ob['SECTIONS']['VALUE'], 'path' => $ob['LINK']['VALUE'], 'name' => (isset($ob['H1']['~VALUE']['TEXT'])) ? $ob['H1']['~VALUE']['TEXT'] : $ob['H1']['~VALUE']];
            }

            return true;
        }

        if (empty($arFields['PROPERTIES'])) {

            //если страница не найдена, то ищем среди шаблонов

            //Разбиваем ссылку на коды
            //начиная с конца поочереди ищем коды пока не найдем последний рабочий из каталога

            //также ищем адрес среди имеющихся тегов()

            $bdir = $APPLICATION->GetCurPage();
            $first_teg = false;

            $arFieldsTeg = [];
            $mdir = explode("/", trim($bdir, "/"));
            $arr_path = [];
            $mdirtemp = $mdir;
            $last_dirs = [];
            //определяем список свойств для шаблона, последнюю секцию, последний тег идущий перед шаблоном (если он был)
            $obj_last_dir = [];
            $last_dir = '';
            for ($i = count($mdir) - 1; $i >= 0; --$i) {

                if ($last_dir == $mdir[$i]) {
                    $arr_paths = [];
                    break;}
                $last_dir = $mdir[$i];

                if ($mdir[$i] == 'catalog') {
                    break;
                }

                //$resdir=CIBlockSection::GetList([],['IBLOCK_ID'=>$arParams['IBLOCK_ID'],'CODE'=>$mdir[$i]],['ELEMENT_SUBSECTIONS']);
                $resdir = CIBlockSection::GetList([],
                    ['IBLOCK_ID' => $arParams['IBLOCK_ID'],
                        'CODE' => $mdir[$i]],
                    false,
                    ['IBLOCK_ID', 'ID', 'SECTION_PAGE_URL', 'NAME', 'IBLOCK_SECTION_ID']);
                $last_dirs = $mdirtemp;

                $cpage1 = '/' . implode('/', $mdirtemp);
                $cpage2 = $cpage1 . '/';
                array_pop($mdirtemp);
                $b_teg_dir = implode('/', $last_dirs);

                $res = CIBlockElement::GetList(array(),
                    ['ACTIVE' => 'Y',
                        'IBLOCK_ID' => $arParams['IBLOCK_TEGS'],
                        ['LOGIC' => 'OR', ['=PROPERTY_LINK' => $cpage1], ['=PROPERTY_LINK' => $cpage2]]]);

                $teg_simple = false;

                if ($ob = $res->GetNextElement()) {
                    $teg_simple = true;

                    if (!$first_teg) {

                        $arFieldsTeg = $ob->GetFields();
                        $arFieldsTeg['PROPERTIES'] = $ob->GetProperties();
                        $first_teg = true;}
                }

                $flg_fnd = false;
                while ($obj = $resdir->GetNext()) {
                    if (trim($obj['SECTION_PAGE_URL'], '/') == trim($cpage1, '/')) {
                        $obj_last_dir = $obj;
                        $flg_fnd = true;
                        $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $obj_last_dir['ID']);
                        $ipropValues_last_dir = $ipropValues->getValues();
                    }
                }

                if ($flg_fnd) {break;}
                
                $arr_path[] = $mdir[$i];
                $arr_paths[] = $arr_path;
            }



            foreach ($arr_paths as $arr_path) {
                if(!empty($arFieldsTeg)&&(count($arr_path)+count(explode('/',trim($arFieldsTeg['PROPERTIES']['LINK']['VALUE'],'/')))<count($mdir))){
                    continue;
                    
                }
                if (!empty($arr_path)) {
                    //получаем список свойств
                    $sRes = CIBlockElement::GetList(array(),
                        ['ACTIVE' => 'Y',
                            'IBLOCK_ID' => $arParams['IBLOCK_TEGS'],
                            '!PROPERTY_PATTERN' => false],
                        false,
                        false,
                        ['IBLOCK_ID', 'ID', 'PROPERTY_PATTERN']
                    );
                    $patterns = [];
                    while ($ob = $sRes->GetNext()) {
                        $patterns[$ob['~PROPERTY_PATTERN_VALUE']] = $ob['~PROPERTY_PATTERN_VALUE'];
                    }

                    $arFields = [];
   
                    if (!empty($patterns)) {
                        $props = [];
                        foreach ($patterns as $pattern) {
                            $resProp = CIBlock::GetProperties($arParams['IBLOCK_ID'], array(), array("CODE" => $pattern));
                            $res_arr = $resProp->Fetch();
                            $props[$res_arr["CODE"]] = $res_arr;
                        }
                        $codes_values = [];

                        foreach ($props as $key => $item_prop) {

                            if ($item_prop['PROPERTY_TYPE'] == 'E') {
                                $g_res = CIBlockElement::GetList(array(),
                                    ['ACTIVE' => 'Y',
                                        'IBLOCK_ID' => $item_prop['LINK_IBLOCK_ID']], ["CODE"]
                                );
                                $codes = [];
                                $codes_real = [];
                                while ($ob = $g_res->Fetch()) {
                                    $codes[] = str_replace('-', '_', $ob['CODE']);
                                    $codes_real[] = $ob['CODE'];
                                }

                                foreach ($arr_path as $item) {
                                    $index = array_search($item, $codes);
                                    if ($index !== false) {
                                        $codes_values[$key]['VALUE'] = $codes[$index];

                                        $g_res = CIBlockElement::GetList(
                                            array(),
                                            ['ACTIVE' => 'Y', 'IBLOCK_ID' => $item_prop['LINK_IBLOCK_ID'], 'CODE' => $codes_real[$index]],
                                            false,
                                            false,
                                            ['IBLOCK_ID', 'ID', 'NAME', 'CODE']);

                                        $ob = $g_res->Fetch();
                                        $ob['TYPE'] = 'E';
                                        $codes_values[$key]['PROPS'] = $ob;
                                    }}
                            }

                            if ($item_prop['PROPERTY_TYPE'] == 'S') {
                                $g_res = CIBlockElement::GetList(array(),
                                    ['ACTIVE' => 'Y',
                                        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                        'SECTION_ID' => [$obj_last_dir['ID']],
                                        "INCLUDE_SUBSECTIONS" => "Y"], ["PROPERTY_SERIAL"]
                                );
                                $codes = [];
                                $codes_real = [];
                                while ($ob = $g_res->GetNext()) {
                                    if (empty($ob['~PROPERTY_SERIAL_VALUE'])) {continue;}
                                    $params_tr = array("replace_space" => "_", "replace_other" => "_");
                                    $codes[] = Cutil::translit($ob['~PROPERTY_SERIAL_VALUE'], "ru", $params_tr);
                                    $codes_name[] = $ob['~PROPERTY_SERIAL_VALUE'];
                                }

                                foreach ($arr_path as $item) {
                                    $index = array_search($item, $codes);
                                    if ($index !== false) {
                                        $codes_values[$key]['VALUE'] = $codes[$index];
                                        $codes_values[$key]['PROPS'] = ['TYPE' => 'S', 'NAME' => $codes_name[$index], 'CODE' => $codes[$index], 'VALUE' => $codes_name[$index]];

                                    }
                                }

                            }

                        }



//заменяем коды соответствующим шаблоном
                        $fdir1 = $bdir;
                        $fdir2 = implode('/', array_reverse($arr_path));
                        foreach ($codes_values as $kod => $item) {
                            $fdir1 = str_replace('/' . $item['VALUE'] . '/', '/#' . $kod . '#/', $fdir1);
                            $fdir2 = str_replace($item['VALUE'], '#' . $kod . '#', $fdir2);
                        }


//Ищем соответствующий шаблон

                        $cpage1 = trim($fdir1, '/') . '/';
                        $cpage2 = trim($fdir1, '/');
                        $cpage3 = trim($fdir2, '/') . '/';
                        $cpage4 = trim($fdir2, '/');

                        $res = CIBlockElement::GetList(array(),
                            ['ACTIVE' => 'Y',
                                'IBLOCK_ID' => $arParams['IBLOCK_TEGS'],
                                ['LOGIC' => 'OR', ['PROPERTY_LINK' => $cpage1],
                                    ['PROPERTY_LINK' => $cpage2],
                                    ['PROPERTY_LINK' => $cpage3],
                                    ['PROPERTY_LINK' => $cpage4]]]);

                        while ($ob = $res->GetNextElement()) {
                            $arFields = $ob->GetFields();
                            $arFields['PROPERTIES'] = $ob->GetProperties();
                        }

                        if (!empty($arFields)) {

                            $arFields['IS_SHABLON'] = true;
                            foreach ($codes_values as $kod => $item) {
                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['NAME']['~VALUE']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $arFields['PROPERTIES']['NAME']['VALUE']);
                                $kode_val = urlencode($item['PROPS']['CODE']);
                                $name_val = urlencode($item['PROPS']['NAME']);
                                $arFields['PROPERTIES']['FILTER_URL']['~VALUE'] = str_replace('#' . $kod . '#', mb_strtolower(str_replace('-', '_', $kode_val)), $arFields['PROPERTIES']['FILTER_URL']['~VALUE']);
                                $arFields['PROPERTIES']['FILTER_URL']['VALUE'] = str_replace('#' . $kod . '#', mb_strtolower(str_replace('-', '_', $kode_val)), $arFields['PROPERTIES']['FILTER_URL']['VALUE']);
                                $arFields['PROPERTIES']['FILTER_URL']['~VALUE'] = str_replace('#' . $kod . '_NAME#', mb_strtolower(str_replace('-', '_', $name_val)), $arFields['PROPERTIES']['FILTER_URL']['~VALUE']);
                                $arFields['PROPERTIES']['FILTER_URL']['VALUE'] = str_replace('#' . $kod . '_NAME#', mb_strtolower(str_replace('-', '_', $name_val)), $arFields['PROPERTIES']['FILTER_URL']['VALUE']);
                            }

                            if ($arFieldsTeg['PROPERTIES']['FILTER_URL']['~VALUE'] && count($arFields['PROPERTIES']['PATTERN']['VALUE']) < 2) {
                                $shabl_url = str_replace('filter/', '', trim($arFields['PROPERTIES']['FILTER_URL']['~VALUE'], '/'));
                                $shabl_url = str_replace('/apply', '', $shabl_url);
                                $arFields['PROPERTIES']['FILTER_URL']['~VALUE'] = $arFieldsTeg['PROPERTIES']['FILTER_URL']['~VALUE'];
                                $arFields['PROPERTIES']['FILTER_URL']['VALUE'] = $arFieldsTeg['PROPERTIES']['FILTER_URL']['~VALUE'];
                                $arFields['PROPERTIES']['FILTER_URL']['~VALUE'] = str_replace('/apply', '/' . $shabl_url . '/apply/', $arFields['PROPERTIES']['FILTER_URL']['~VALUE']);
                                $arFields['PROPERTIES']['FILTER_URL']['VALUE'] = str_replace('/apply', '/' . $shabl_url . '/apply/', $arFields['PROPERTIES']['FILTER_URL']['VALUE']);

                                $name_val = $arFieldsTeg['PROPERTIES']['NAME']['VALUE'];

                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] =
                                    str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['NAME']['~VALUE']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['NAME']['VALUE']);

                                $name_val = $arFieldsTeg['PROPERTIES']['H1']['~VALUE']['TEXT'];
                                //$name_val=$ipropValues_last_dir["ELEMENT_PAGE_TITLE"];

                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] =
                                    str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['NAME']['~VALUE']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['NAME']['VALUE']);

                                $name_val_lv = mb_strtolower(mb_substr(trim($name_val), 0, 1)) . mb_substr(trim($name_val), 1);
                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] =
                                    str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['NAME']['~VALUE']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['NAME']['VALUE']);
                            } else {
                                $arFields['PROPERTIES']['FILTER_URL']['~VALUE'] = '/' . implode('/', $last_dirs) . $arFields['PROPERTIES']['FILTER_URL']['~VALUE'];
                                $arFields['PROPERTIES']['FILTER_URL']['VALUE'] = '/' . implode('/', $last_dirs) . $arFields['PROPERTIES']['FILTER_URL']['VALUE'];

                                $name_val = $obj_last_dir['NAME'];

                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] =
                                    str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['NAME']['~VALUE']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#SECT_NAME#', $name_val, $arFields['PROPERTIES']['NAME']['VALUE']);

                                $name_val = $ipropValues_last_dir["ELEMENT_PAGE_TITLE"];

                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['NAME']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#SECT_H1#', $name_val, $arFields['PROPERTIES']['NAME']['VALUE']);

                                $name_val_lv = mb_strtolower(mb_substr(trim($name_val), 0, 1)) . mb_substr(trim($name_val), 1);
                                $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['H1']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['~VALUE'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['NAME']);
                                $arFields['PROPERTIES']['H1']['VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['H1']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['TITLE']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['DESCRIPTION']['VALUE']['TEXT']);
                                $arFields['PROPERTIES']['NAME']['VALUE'] = str_replace('#SECT_H1_LC#', $name_val_lv, $arFields['PROPERTIES']['NAME']['VALUE']);

                            }
                            break;
                        }    
                    } 
                } 
            }
            
            if(count($arr_paths)!=count($codes_values)&&empty($arFieldsTeg)){$arFields['PROPERTIES']=[];}
        }
//var_dump([count($arr_paths),count($codes_values)]);
//var_dump($arFields['PROPERTIES']);
        //если страница найдена
        if (!empty($arFields['PROPERTIES'])) {

            $filter = $arFields['PROPERTIES']['FILTER_URL']['~VALUE'];

            $filter = trim($filter, '/');
            $filter = str_replace(['catalog/', 'http://', 'https://', $_SERVER['SERVER_NAME'] . '/'], '', $filter);
            //$filter       = urldecode($filter);
            $filter_path = $filter;
            $arrUrlFilter = explode('/', $filter);
            $pos = array_search('filter', $arrUrlFilter);
            $filter = array_slice($arrUrlFilter, $pos + 1, count($arrUrlFilter) - $pos - 2);
            $path = array_slice($arrUrlFilter, 0, $pos);
            $f_filter = implode('/', $filter);
            $f_path = implode('/', $path);
            $dirCode = array_pop($path);

            $arrFilterTemp = [];
            foreach ($filter as $item) {
                $item = urldecode($item);
                $params = explode('-is-', $item);

                if (!empty($params[0]) && !empty($params[1])) {$arrFilterTemp[mb_strtoupper($params[0])] = ["=" => explode('-or-', $params[1])];}
                if (count($params == 1)) {
                    $pos_from = strpos($item, '-from-');
                    $pos_to = strpos($item, '-to-');
                    $params = explode('-', $item);

                    if ($pos_from !== false && $pos_to === false) {
                        $to = false;
                        $from = substr($item, $pos_from + 6);
                        $arrFilterTemp[mb_strtoupper($params[0])] = ['>=' => floatval($from)];
                    }
                    if ($pos_to !== false && $pos_from === false) {
                        $from = false;
                        $to = substr($item, $pos_to + 4);
                        $arrFilterTemp[mb_strtoupper($params[0])] = ['<=' => floatval($to)];}
                    if ($pos_from !== false && $pos_to !== false) {
                        $from = substr($item, $pos_from + 6, $pos_to - 6 - $pos_from);
                        $to = substr($item, $pos_to + 4);
                        $arrFilterTemp[mb_strtoupper($params[0])] = ['>=' => floatval($from), '<=' => floatval($to)];
                    }

                }
            }

            $property = CIBlockProperty::GetList(array("DEF" => "DESC", "SORT" => "ASC"),
                array("IBLOCK_ID" => $arParams['IBLOCK_ID']));
            $prop_type = [];
            $prop_type_link = [];
            while ($enum_fields = $property->GetNext()) {
                $prop_type[mb_strtoupper($enum_fields['CODE'])] = $enum_fields['PROPERTY_TYPE'];
                if (!empty($enum_fields['LINK_IBLOCK_ID'])) {$prop_type_link[mb_strtoupper($enum_fields['CODE'])] = $enum_fields['LINK_IBLOCK_ID'];}

            }

            $res = CIBlockSection::GetList([], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $dirCode], false, ['IBLOCK_ID', 'ID', 'CODE', 'SECTION_PAGE_URL']);
            if ($res) {
                $ob = $res->GetNext();
                $dirId = $ob['ID'];
                $tegPageUrl = $ob['SECTION_PAGE_URL'];
            }

            $arrFilterProp = [];

            foreach ($arrFilterTemp as $key => $item) {

                if ($prop_type[mb_strtoupper($key)] == 'N') {
                    if (isset($item[">="])) {
                        $arrFilterProp['>=PROPERTY_' . $key] = $item[">="];
                    }

                    if (isset($item["<="])) {
                        $arrFilterProp['<=PROPERTY_' . $key] = $item["<="];
                    }

                }

                if ($prop_type[mb_strtoupper($key)] == 'L') {
                    foreach ($item["="] as $val) {
                        $arrFilterProp['PROPERTY_' . $key . '_VALUE'][] =
                        CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "XML_ID" => $val))->GetNext()["VALUE"];
                    }
                }

                if ($prop_type[mb_strtoupper($key)] == 'S') {$arrFilterProp['PROPERTY_' . $key] = $item["="];}

                if ($prop_type[mb_strtoupper($key)] == 'E') {

                    $g_res = CIBlockElement::GetList(
                        array(),
                        ['ACTIVE' => 'Y', 'IBLOCK_ID' => $prop_type_link[mb_strtoupper($key)], 'CODE' => $item["="]],
                        false,
                        false,
                        ['IBLOCK_ID', 'ID']);

                    $ob = $g_res->Fetch();

                    if (!empty($ob['ID'])) {$arrFilterProp['PROPERTY_' . $key] = $ob['ID'];}
                }
            }

//сложная логика для объединения трех подзапросов - по секции, по фильтру и набор элементов.

            $arrFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
            $arrFilter["SECTION_ID"] = false;
            $arrFilter["INCLUDE_SUBSECTIONS"] = "Y";
            $logic = ["LOGIC" => "OR"];
            if (!empty($arrFilterProp)) {
                $arrFilterProp['SECTION_ID'] = [$dirId];
                $arrFilterProp["INCLUDE_SUBSECTIONS"] = "Y";
                $logic[] = $arrFilterProp;
            }

            if (!empty($arFields['PROPERTIES']['FILTER_SECTIONS']['VALUE'])) {
                $logic[] = ['SECTION_ID' => $arFields['PROPERTIES']['FILTER_SECTIONS']['VALUE']];
            }

            if (!empty($arFields['PROPERTIES']['FILTER_ELEMENTS']['VALUE'])) {
                $logic[] = ['ID' => $arFields['PROPERTIES']['FILTER_ELEMENTS']['VALUE']];
            }

            $arrFilter[] = $logic;

            $componentPage = 'teg';
            $arVariables["SECTION_ID"] = $dirId;
            $arVariables["ELEMENT_ID"] = null;
            $arVariables["SECTION_CODE"] = $dirCode;
            $arVariables["ELEMENT_CODE"] = null;
            $arVariables["FILTER"] = $arrFilter;
            $arVariables['SEO']["TITLE"] = $arFields['PROPERTIES']['TITLE']['~VALUE']['TEXT'];
            $arVariables['SEO']["DESCRIPTION"] = $arFields['PROPERTIES']['DESCRIPTION']['~VALUE']['TEXT'];
            $arVariables['SEO']["H1"] = $arFields['PROPERTIES']['H1']['~VALUE']['TEXT'];
            $arVariables['SEO']["SERIES"] = $arFields['PROPERTIES']['SERIES_NAME']['~VALUE'];
            $arVariables['PAGE'] = $componentPage;
            $arVariables["TEGS"][] = $arFields['ID'];
            if (!empty($arFields['PROPERTIES']['TEGS']['VALUE'])) {$arVariables["TEGS"] = array_merge($arVariables["TEGS"], $arFields['PROPERTIES']['TEGS']['VALUE']);}
            $arVariables["SECTION_CODE_PATH"] = $filter_path;
            $arVariables["TEG_ID"] = $arFields['ID'];

            $arVariables["TEG_TEXT_BOTTOM"] = $arFields['PROPERTIES']['TEXT_BOTTOM']['~VALUE']['TEXT'];

            $handler = Bitrix\Main\EventManager::getInstance()->addEventHandler("main", "OnEpilog", 'fseo_cat');

            $GLOBALS['ARRSEO'] = $arVariables['SEO'];

            $ids = [];
            $tempChain = [];

            $ID = empty($arFields['IS_SHABLON']) ? $arFields['ID'] : (!empty($arFieldsTeg['ID']) ? $arFieldsTeg['ID'] : '');
            $ID = !empty($arFields['IS_SHABLON']) ? '' : $ID;

            $arr_path_max = $arr_paths[count($arr_paths) - 1];

            array_shift($arr_path_max);

            if (!empty($ID)) {$ne_zacicl = getChainToTeg($tempChain, $ID, $arFields['IBLOCK_ID'], $ids, $ID);} else {
                $ID = $arFields['ID'];
                $ne_zacicl = getChainToTegShablon($tempChain, $ID, $arFields['IBLOCK_ID'], $ids, $arr_path_max, $arVariables["SECTION_ID"]);

                foreach ($tempChain as &$chain) {

                    foreach ($codes_values as $kod => $item) {
                        $chain['name'] = str_replace('#' . $kod . '_NAME#', $item['PROPS']['NAME'], $chain['name']);
                        $name_val = $obj_last_dir['NAME'];
                        $chain['name'] = str_replace('#SECT_NAME#', $name_val, $chain['name']);
                        $name_val = $ipropValues_last_dir["ELEMENT_PAGE_TITLE"];
                        $chain['name'] = str_replace('#SECT_H1#', $name_val, $chain['name']);
                        $name_val_lv = mb_strtolower(mb_substr($name_val, 0, 1)) . mb_substr($name_val, 1);
                        $chain['name'] = str_replace('#SECT_H1_LC#', $name_val_lv, $chain['name']);
                        if (mb_substr($chain['path'], 0, 1) != '/') {$chain['path'] = str_replace('#' . $kod . '#', mb_strtolower(str_replace('-', '_', $item['PROPS']['CODE'])), '/' . implode('/', $last_dirs) . '/' . $chain['path']);} else {
                            $chain['path'] = str_replace('#' . $kod . '#', mb_strtolower($item['PROPS']['CODE']), $chain['path']);
                        }
                    }
                }
            }

            if (!empty($tempChain)) {$sectionFirst = $tempChain[0]['section'];} elseif (empty($arFields['IS_SHABLON'])) {$sectionFirst = $arFields['PROPERTIES']['SECTIONS']['VALUE'][0];}
            if (!empty($arFields['IS_SHABLON'])) {
                $sectionFirst = $obj_last_dir['ID'];
                $arVariables["PARENT_ID"] = $obj_last_dir['IBLOCK_SECTION_ID'];
            }

            $list = CIBlockSection::GetNavChain(false, $sectionFirst);

            $pathChain = [];
            while ($arSectionPath = $list->GetNext()) {
                $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arSectionPath['ID']);
                $IPROPERTY = $ipropValues->getValues();
                $pathChain[] = ['path' => $arSectionPath['SECTION_PAGE_URL'], 'name' => (!empty($IPROPERTY['SECTION_PAGE_TITLE'])) ? $IPROPERTY['SECTION_PAGE_TITLE'] : $arSectionPath['~NAME']];
            }

            if (empty($arFields['IS_SHABLON']) && $ne_zacicl || $arFields['IS_SHABLON'] && $ne_zacicl/*&&$first_teg*/) {
                $pathChain = array_merge($pathChain, $tempChain);
            }

            $GLOBALS['NAVCHAIN_ADD'] = $arParams["ADD_SECTIONS_CHAIN"];
            $arParams["ADD_SECTIONS_CHAIN"] = "N";

            $GLOBALS['NAVCHAIN'] = $pathChain;
            $arVariables["TEG_SECTION_URL"] = $pathChain[count($pathChain) - 1]['path'];

            function fseo_cat()
            {
                //$GLOBALS['APPLICATION']->SetTitle($GLOBALS['ARRSEO']['H1']);
                $GLOBALS['APPLICATION']->SetPageProperty('page-title', '<h1 class="page-title page__page-title">' . $GLOBALS['ARRSEO']['H1'] . '</h1>');
                $GLOBALS['APPLICATION']->SetPageProperty('title', $GLOBALS['ARRSEO']['TITLE']);
                $GLOBALS['APPLICATION']->SetPageProperty('description', $GLOBALS['ARRSEO']['DESCRIPTION']);
                $pathChain = $GLOBALS['NAVCHAIN'];
                $pathChain[] = ['path' => false, 'name' => $GLOBALS['ARRSEO']['H1']];
                if ($GLOBALS['NAVCHAIN_ADD'] == "Y") {
                    foreach ($pathChain as $key => $nav) {
                        $GLOBALS['APPLICATION']->AddChainItem($nav['name'], '/' . trim($nav['path'], '/') . '/');
                    }
                }

            }

        }
    }

    if ($componentPage === "smart_filter") {
        $componentPage = "section";
    }

    if (!$componentPage && isset($_REQUEST["q"])) {
        $componentPage = "search";
    }

    $b404 = false;
    if (!$componentPage) {
        $componentPage = "sections";
        $b404 = true;
    }

    if ($componentPage == "section") {
        if (isset($arVariables["SECTION_ID"])) {
            $b404 |= (intval($arVariables["SECTION_ID"]) . "" !== $arVariables["SECTION_ID"]);
        } else {
            $b404 |= !isset($arVariables["SECTION_CODE"]);
        }

    }

    if ($b404 && CModule::IncludeModule('iblock')) {
        $folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
        if ($folder404 != "/") {
            $folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
        }

        if (mb_substr($folder404, -1) == "/") {
            $folder404 .= "index.php";
        }

        if ($folder404 != $APPLICATION->GetCurPage(true)) {
            \Bitrix\Iblock\Component\Tools::process404(
                ""
                , ($arParams["SET_STATUS_404"] === "Y")
                , ($arParams["SET_STATUS_404"] === "Y")
                , ($arParams["SHOW_404"] === "Y")
                , $arParams["FILE_404"]
            );
        }
    }

    CComponentEngine::initComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
    $arResult = array(
        "FOLDER" => $arParams["SEF_FOLDER"],
        "URL_TEMPLATES" => $arUrlTemplates,
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases,
    );
} else {
    $arVariables = array();

    $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

    $componentPage = "";

    $arCompareCommands = array(
        "COMPARE",
        "DELETE_FEATURE",
        "ADD_FEATURE",
        "DELETE_FROM_COMPARE_RESULT",
        "ADD_TO_COMPARE_RESULT",
        "COMPARE_BUY",
        "COMPARE_ADD2BASKET",
    );

    if (isset($arVariables["action"]) && in_array($arVariables["action"], $arCompareCommands)) {
        $componentPage = "compare";
    } elseif (isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) {
        $componentPage = "element";
    } elseif (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] != '') {
        $componentPage = "element";
    } elseif (isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0) {
        $componentPage = "section";
    } elseif (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] != '') {
        $componentPage = "section";
    } elseif (isset($_REQUEST["q"])) {
        $componentPage = "search";
    } else {
        $componentPage = "sections";
    }

    $currentPage = htmlspecialcharsbx($APPLICATION->GetCurPage()) . "?";
    $arResult = array(
        "FOLDER" => "",
        "URL_TEMPLATES" => array(
            "section" => $currentPage . $arVariableAliases["SECTION_ID"] . "=#SECTION_ID#",
            "element" => $currentPage . $arVariableAliases["SECTION_ID"] . "=#SECTION_ID#" . "&" . $arVariableAliases["ELEMENT_ID"] . "=#ELEMENT_ID#",
            "compare" => $currentPage . "action=COMPARE",
        ),
        "VARIABLES" => $arVariables,
        "ALIASES" => $arVariableAliases,
    );
}

?>
<script>
    console.log(<?=json_encode($arResult, JSON_UNESCAPED_UNICODE)?>);
</script>
<?

$this->IncludeComponentTemplate($componentPage);
