<?php require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php";?>
<?
CModule::IncludeModule("iblock");

function get_http_status_code($url)
{
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

    /* Get the HTML or whatever is linked in $url. */
    $response = curl_exec($handle);

    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    if ($httpCode == 404) {
        /* Handle 404 here. */
    }

    curl_close($handle);
    return $httpCode;
}

function mb_substr_replace($original, $replacement, $position, $length)
{
    $startString = mb_substr($original, 0, $position, 'UTF-8');
    $endString = mb_substr($original, $position + $length, mb_strlen($original), 'UTF-8');
    $out = $startString . $replacement . $endString;
    return $out;
}

$arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = array("IBLOCK_ID" => [14], "ACTIVE" => "Y");
$res = CIBlockElement::GetList(array(), $arFilter, false, ['iNumPage' => 4, 'nPageSize' => 100]);
$count = 0;
$count1=0;
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arFields['~PREVIEW_TEXT'] = str_replace('www.zodiacos.ru', 'zodiacos.ru', $arFields['~PREVIEW_TEXT']);
    $arFields['~PREVIEW_TEXT'] = str_replace('http://zodiacos.ru', 'https://zodiacos.ru', $arFields['~PREVIEW_TEXT']);
    $pos1 = 0;
    $pos2 = 0;
    $positions = [];
    while (($pos1 = mb_strpos($arFields['~PREVIEW_TEXT'], 'href="', $pos2)) !== false) {
        $pos2 = mb_strpos($arFields['~PREVIEW_TEXT'], '"', $pos1 + 6);
        $link = mb_substr($arFields['~PREVIEW_TEXT'], $pos1 + 6, $pos2 - $pos1 - 6);
        if ($link != 'https://zodiacos.ru') {
            $http_status_code = get_http_status_code($link);
            if ($http_status_code != 200) {
                $positions[] = [$pos1, $pos2];
            }
        }
    }
    $posit = array_reverse($positions);
    foreach ($posit as $pos) {
        $arFields['~PREVIEW_TEXT'] = mb_substr_replace($arFields['~PREVIEW_TEXT'], '', $pos[0], ($pos[1] - $pos[0] + 1));
    }

    if (!empty($posit)) {
        $count++;
        
        
        echo $arFields['ID'] . ' , ';
    }
    $count1++;
    $el = new CIBlockElement;
    $res2 = $el->Update($arFields['ID'], ['PREVIEW_TEXT' => $arFields['~PREVIEW_TEXT']]);

}
var_dump([$count,$count1]);

?>
	<?php require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php";
