<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult['ITEMS'] as $key => $item) {
    if (!is_array($item['PROPERTY_FILES_VALUE'])) {
        $arResult['ITEMS'][$key]['PROPERTY_FILES_VALUE']=array($item['PROPERTY_FILES_VALUE']);
    }
}

$arrMonth = [
    '.01.' => ' января ',
    '.02.' => ' февраля ',
    '.03.' => ' марта ',
    '.04.' => ' апреля ',
    '.05.' => ' мая ',
    '.06.' => ' июня ',
    '.07.' => ' июля ',
    '.08.' => ' августа ',
    '.09.' => ' сентября ',
    '.10.' => ' октября ',
    '.11.' => ' ноября ',
    '.12.' => ' декабря '
];


foreach ($arResult['ITEMS'] as $key => $item)
{
    $formdate = date('d.m.Y', strtotime($item['TIMESTAMP_X']));
    foreach($arrMonth as $m=>$mn)
    {
        $formdate=str_replace($m,$mn,$formdate);
    }
    $arResult['ITEMS'][$key]['DATE']=$formdate;

}

$res   = CIBlockElement::GetList([],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID'],
     'PROPERTY_CATALOG' => $arParams['ID'],
     'ACTIVE'=>'Y'],
    false, false, ['ID','PROPERTY_STARS']);
$sum=0;
$count=0;
while ($ob = $res->Fetch())
{
    if(!empty($ob['PROPERTY_STARS_VALUE'])){
        $sum+=(int)$ob['PROPERTY_STARS_VALUE'];
        $count+=1;
    }
}

if($count>0)
{
    $arResult['STARS']=ceil($sum/$count);
    $part=$count%10;
    if($part==1){$txt1='отзыв';}else
    if($part>1&&$part<5){$txt1='отзыва';}else
    if($part>4){$txt1='отзывов';}
    if(in_array($count%100,[11,12,13,14])){$txt1='отзывов';}
    $arResult['STARS_INFO']= $count.' '.$txt1.' ('.$arResult['STARS'].' из 5)';
}

//убираем повторяемость