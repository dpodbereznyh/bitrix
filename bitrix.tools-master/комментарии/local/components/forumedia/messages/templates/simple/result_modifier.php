<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
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
    $arResult['STARS_INFO']= $count.' '.$txt1;
}

//убираем повторяемость