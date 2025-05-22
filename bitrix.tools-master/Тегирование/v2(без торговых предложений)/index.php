<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?php
$APPLICATION->IncludeComponent(
	"forumedia:catalog", 
	"main", 
	array(
		"IBLOCK_TEGS"=>"94",
		.....
	),
	false
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
