<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$arrGet = $request->getQueryList()->toArray();
?>
<?php

foreach ($arrGet as $key => $get){
    if($key == 'PAGEN_1'){
        $page = $get;
    }
}

if(!empty($page)){
    $page = ' (страница '.$page.')';
}
else{
    $page = '';
}

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}
if($APPLICATION->GetCurPage() == "/catalog/muzhchinam/"){
    $APPLICATION->SetPageProperty("title", "Мужская одежда ".$arResult['FILTER']." - Velocity".$page);
    $APPLICATION->SetPageProperty("description", "Купить мужскую одежду с доставкой по Москве и всей России в интернет-магазине ".$arResult['FILTER']." Velocity.ru".$page." - Стильная одежда в стиле casual");
}
elseif ($APPLICATION->GetCurPage() == "/catalog/zhenshchinam/"){
    $APPLICATION->SetPageProperty("title", "Женская одежда - Velocity".$page);
    $APPLICATION->SetPageProperty("description", "Купить женскую одежду с  доставкой по Москве и всей России в интернет-магазине ".$arResult['FILTER']." Velocity.ru".$page." -  Стильная одежда в стиле casual");
}
else{
    $APPLICATION->SetPageProperty("title", $arResult['POL_UP'].' '.mb_strtolower($arResult['NAME']).$arResult['FILTER'].' - Velocity' .$page);
    $APPLICATION->SetPageProperty("description", 'Купить '.$arResult['POL'].' '.mb_strtolower($arResult['NAME']).' с доставкой по Москве и всей России в интернет-магазине '.$arResult['FILTER'].' Velocity.ru'.$page.' - Стильная одежда в стиле casual');
}
$APPLICATION->AddHeadString('<link href="https://' . SITE_SERVER_NAME . $arResult['SECTION_PAGE_URL'] . '" rel="canonical" />', true);

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);
	list(, $epilogue) = explode('<!-- component-end -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer,
		'epilogue' => $epilogue,
	));
}