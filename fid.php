<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("dev");
?>

    <doctors>

<?

$arSelect = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "PRESENT_POST");
$arFilter = array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "ACTIVE_DATE"=>"Y");
$res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
    $arrName = explode(' ', $arFields['NAME']);
    if($arProps['EDUCATION']['VALUE']) {
    $arrEducation = implode(', ', $arProps['EDUCATION']['VALUE']);
    }
    $prevPic = CFile::GetPath($arFields['PREVIEW_PICTURE']);
    ?>
    <doctor>
        <name><?=$arrName[1]?></name>
        <surname><?=$arrName[0]?></surname>
        <midname><?=$arrName[2]?></midname>
        <site_id><?=$arFields['ID']?></site_id>
        <profession><?=$arProps['PRESENT_POST']['VALUE'][0]?></profession>
        <description><?=$arrEducation?></description>
        <profession><?=$arProps['PRESENT_POST']['VALUE'][0]?></profession>
        <experience><?=preg_replace('/[^0-9]/', '', $arProps['EXPERIENCE']['VALUE']) ?></experience>
        <degree><?=$arProps['DOC_ED']['VALUE'] ?></degree>
        <avatar>https://www.goldenmed.ru<?=$prevPic?></avatar>
    </doctor>

    <?php
}

?>
    </doctors>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>