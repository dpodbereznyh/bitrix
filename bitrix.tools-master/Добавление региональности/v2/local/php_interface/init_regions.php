<?
define('FSEO_MAIN_DOMEN', 'teflonopt.ru');
define('IBLOCK_REGIONS', '28');
define('IBLOCK_REGIONS_SEO', '29');
define('IBLOCK_REGIONS_TEXT', '30');

function add_links($IBLOCK_ID, &$elems, &$app)
{

    $elem      = $elems['ID'];
    $arButtons = CIBlock::GetPanelButtons(
        $IBLOCK_ID,
        $elem,
        0,
        array("SECTION_BUTTONS" => false, "SESSID" => false)
    );
    $elems["ADD_LINK"]         = $arButtons["edit"]["add_element"]["ACTION_URL"];
    $elems["EDIT_LINK"]        = $arButtons["edit"]["edit_element"]["ACTION_URL"];
    $elems["DELETE_LINK"]      = $arButtons["edit"]["delete_element"]["ACTION_URL"];
    $elems["ADD_LINK_TEXT"]    = $arButtons["edit"]["add_element"]["TEXT"];
    $elems["EDIT_LINK_TEXT"]   = $arButtons["edit"]["edit_element"]["TEXT"];
    $elems["DELETE_LINK_TEXT"] = $arButtons["edit"]["delete_element"]["TEXT"];
    $app->AddEditAction($elem, $elems['ADD_LINK'], $elems["ADD_LINK_TEXT"]);
    $app->AddEditAction($elem, $elems['EDIT_LINK'], $elems["EDIT_LINK_TEXT"]);
    $app->AddDeleteAction($elem, $elems['DELETE_LINK'], $elems["DELETE_LINK_TEXT"], array("CONFIRM" => 'Точно удалить?'));
    $elems["AREA_ID"] = $app->GetEditAreaID($elem);

}

function calculateTheDistance($φA, $λA, $φB, $λB)
{

// перевести координаты в радианы
    $lat1  = $φA * M_PI / 180;
    $lat2  = $φB * M_PI / 180;
    $long1 = $λA * M_PI / 180;
    $long2 = $λB * M_PI / 180;

// косинусы и синусы широт и разницы долгот
    $cl1    = cos($lat1);
    $cl2    = cos($lat2);
    $sl1    = sin($lat1);
    $sl2    = sin($lat2);
    $delta  = $long2 - $long1;
    $cdelta = cos($delta);
    $sdelta = sin($delta);

// вычисления длины большого круга
    $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
    $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;

//
    $ad = atan2($y, $x);

    return $ad;
}

function getCurrPlace(&$result)
{
    $site = trim($_SERVER['HTTP_HOST'], 'www.');
    if (empty($_REQUEST['region'])) {
        //$ipAddress = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
        //\Bitrix\Main\Service\GeoIp\Manager::useCookieToStoreInfo(true);
        //$res = \Bitrix\Main\Service\GeoIp\Manager::getGeoPosition($ipAddress, 'ru');
        //$GLOBALS['REG']['region']=$res;
        if (!empty($res)) {
            $cos1   = cos(deg2rad($res['latitude']));
            $sin1   = sin(deg2rad($res['latitude']));
            $coords = [];
            foreach ($result['DOMENS'] as $dom => $item) {
                if (!empty($item['MAP'])) {
                    $temp = explode(',', $item['MAP']['VALUE']);
                    $d    = calculateTheDistance($temp[0], $temp[1], $res['latitude'], $res['longitude']);

                    if ($d < $cd) {
                        $cd   = $d;
                        $site = $dom;
                    }
                }

            }}}
    return $site;

}

function current_regions()
{
    CModule::IncludeModule("iblock");
    $result  = [];
    $resList = CIBlockElement::GetList(['SORT' => 'ASC'],
        ['IBLOCK_ID' => IBLOCK_REGIONS, 'ACTIVE' => 'Y'],
        false,
        false,
        ['IBLOCK_ID', 'ID', 'NAME', 'CODE', 'PROPERTY_DOMEN', 'PROPERTY_MAP']);

    while ($ob=$resList->GetNext()) 
    {   $ob['DOMEN']=$ob['PROPERTY_DOMEN'];
        $ob['MAP']=$ob['PROPERTY_MAP'];
        $result['DOMENS'][$ob['PROPERTY_DOMEN_VALUE']] = $ob;
    }

    $dom                     = getCurrPlace($result);
    $GLOBALS['REG']['site']  = $dom;
    $GLOBALS['REG']['site1'] = trim($_SERVER['HTTP_HOST'], 'www.');
    if (trim($_SERVER['HTTP_HOST'], 'www.') != $dom) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: https://" . $dom . $GLOBALS['APPLICATION']->GetCurPage());
        exit();
    }

    $res = CIBlockElement::GetList(['SORT' => 'ASC'],
        ['IBLOCK_ID' => IBLOCK_REGIONS, 'ACTIVE' => 'Y', 'PROPERTY_DOMEN' => [$dom, FSEO_MAIN_DOMEN]],
        false,
        false,
        ['IBLOCK_ID', 'ID', 'NAME', 'CODE']);
    $active = [];
    while ($ob= $res->GetNextElement()) {
        $temp=$ob->GetFields(); 
        $temp=array_merge($temp,$ob->GetProperties()); 
        $active[$temp['DOMEN']['VALUE']] = $temp;
    }
    $result['ACTIVE'] = (!empty($active[$dom])) ? $active[$dom] : $active[FSEO_MAIN_DOMEN];

    add_links(IBLOCK_REGIONS, $result['ACTIVE'], new CBitrixComponent());
    return $result;
}

function isMainDomain()
{
    return !empty($GLOBALS['REGIONS']['ACTIVE']['DOMEN']['VALUE']) && count(explode('.', $GLOBALS['REGIONS']['ACTIVE']['DOMEN']['VALUE'])) == 2;
}

$GLOBALS['REGIONS'] = current_regions();
