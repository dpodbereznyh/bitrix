<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
$APPLICATION->IncludeComponent(
	"forumedia:sity",
	"",
	Array('IBLOCK_ID'=> IBLOCK_REGIONS_SEO,
		  'CACHE_TYPE'=>'N',
		  'QUERY'=>trim($_REQUEST['query']),
		  'SITY'=>'Y',
		  'FORM'=>trim($_REQUEST['form']),
		  'PAGE'=>trim($_REQUEST['page'])),
	false);
die();