<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
header('content-type: text/txt');

$sub=str_replace(FSEO_MAIN_DOMEN,"",trim($_SERVER['HTTP_HOST'],'www.'));
$find=!empty($GLOBALS['REGIONS']["DOMENS"][(empty($sub)?'':$sub).FSEO_MAIN_DOMEN]);
if($find){
	$content=file_get_contents("robots/".(empty($sub)?'':'region.')."robots.txt");
}else{
	$content=file_get_contents("robots/disallow.robots.txt");
}
$content=str_replace([FSEO_MAIN_DOMEN,"www.".FSEO_MAIN_DOMEN],$sub.FSEO_MAIN_DOMEN,$content);
echo $content;
?>