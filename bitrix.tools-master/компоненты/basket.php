<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("tags", "Zodiacos.ru Интернет-магазин натуральной корейской косметики Kims, Limoni, Richenna, The Plant Base. Корзина");
$APPLICATION->SetPageProperty("keywords_inner", "Zodiacos.ru Интернет-магазин натуральной корейской косметики Kims, Limoni, Richenna, The Plant Base. Корзина");
$APPLICATION->SetPageProperty("title", "Zodiacos.ru Интернет-магазин натуральной корейской косметики Kims, Limoni, Richenna, The Plant Base. Корзина");
$APPLICATION->SetPageProperty("keywords", "Zodiacos.ru Интернет-магазин натуральной корейской косметики Kims, Limoni, Richenna, The Plant Base. Корзина");
$APPLICATION->SetPageProperty("description", "Zodiacos.ru Интернет-магазин натуральной корейской косметики Kims, Limoni, Richenna, The Plant Base. Корзина");
$APPLICATION->SetTitle("Корзина");?>

<?$APPLICATION->IncludeComponent(
	"forumedia:basket",
	"basket",
	Array(
		'LID'=>'b1',
		'OUT_LID'=>'s1',
		'MIN_TOTAL'=>!empty(BASKET_MIN_TOTAL)?BASKET_MIN_TOTAL:''
	)
);?>

<?$APPLICATION->IncludeComponent(
	"forumedia:basket",
	"favorite",
	Array(
		'LID'=>'f1',
		'OUT_LID'=>'s1'
	)
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>