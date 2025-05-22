<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
CModule::IncludeModule("iblock");

if($_REQUEST['generate']=='Y')
{
$now=date("Y-m-d\TH:i:s");

$arrURL=[/*'s1'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/'],
    's2'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/oblasti-primenenija-jeksmash/'],
    's3'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/kontakty/'],
    's4'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/uslugi/'],
    's5'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/uslugi/lizing/'],
    's6'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/uslugi/servis/'],
    's7'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/o-kompanii/'],
    's8'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/o-kompanii/galereya/'],
    's9'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/o-kompanii/video/'],
    's10'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/o-kompanii/sertifikaty/'],
    's11'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/o-kompanii/geografiya-prodazh/'],
    's12'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/projects/'],
    's13'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/news/'],
    's14'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/stati/'],*/
    's0'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/'],
    's1'=>['TIMESTAMP_X'=>$now,'DETAIL_PAGE_URL'=>'/catalog/']];


$res = CIBlockElement::GetList(
    array(),
    array('IBLOCK_ID' => array(17),'ACTIVE'=>'Y'),
    false,
    false,
    array('ID','CODE','DETAIL_PAGE_URL','TIMESTAMP_X')
);
while($elem_cod=$res->GetNext()){
    $elem_cod['TIMESTAMP_X']=date("Y-m-d\TH:i:s",strtotime($elem_cod['TIMESTAMP_X']));
    $db_old_groups = CIBlockElement::GetElementGroups($elem_cod['ID'], false,['SECTION_PAGE_URL']);
    $ar_new_groups=[];
    while($ar_group = $db_old_groups->GetNext())
            {$elem_cod['DETAIL_PAGE_URL']=$ar_group['SECTION_PAGE_URL'].$elem_cod['CODE'].'/';
            $arrURL[]=$elem_cod;
            }
}
$resSect = CIBlockSection::GetList(
    array(),
    array('IBLOCK_ID' => array(17),'ACTIVE'=>'Y'),
    false,
    array('ID','SECTION_PAGE_URL','TIMESTAMP_X')
);

while($elem_cod=$resSect->GetNext()){
    $elem_cod['TIMESTAMP_X']=date("Y-m-d\TH:i:s",strtotime($elem_cod['TIMESTAMP_X']));
    $arrURL[]=$elem_cod;
}

$urlbase = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

$xml='<?xml version="1.0" encoding="UTF-8"?>
    <!--	created with www.mysitemapgenerator.com	-->
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
foreach($arrURL as $item){
    $time=$item['TIMESTAMP_X'];
    $url=(!empty($item['DETAIL_PAGE_URL']))?$item['DETAIL_PAGE_URL']:$item['SECTION_PAGE_URL'];
    if(empty($url)){continue;}
    $url=$urlbase.$url;
    $xml.="<url>\n<loc>$url</loc>\n<lastmod>$time</lastmod>\n</url>\n";
}
$xml.='</urlset>';
$result=file_put_contents($_SERVER['DOCUMENT_ROOT'].'/karta-sayta/map.xml',$xml);
if($result) echo "<p>Генерация прошла успешно</p><p>Сгенерировано ".count($arrURL)." ссылок</p>";
if(!$result) echo "<p>Ошибка генерации!</p>";
}
else{
   ?>
    <h2>Генерация карты сайта</h2>
    <form action="/karta-sayta/generate.php" method="POST">
        <input type="hidden" value="Y" name="generate"/>
        <input type="submit" value="сгенерировать" class="btn btn-primary"/>
    </form>
    <?php
}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>