<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
header('content-type: text/xml');
$sub=str_replace(FSEO_MAIN_DOMEN,"",trim($_SERVER['HTTP_HOST'],'www.'));
$content=file_get_contents("sitemaps/".(empty($sub)?'':'region.')."sitemap.xml");
$content=str_replace([FSEO_MAIN_DOMEN,"www.".FSEO_MAIN_DOMEN],$sub.FSEO_MAIN_DOMEN,$content);
echo $content;
?>