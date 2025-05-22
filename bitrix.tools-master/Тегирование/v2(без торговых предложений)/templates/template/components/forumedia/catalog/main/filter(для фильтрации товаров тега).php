
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		$template,
		Array(
			...........
			"PREFILTER_NAME"=>($arResult['VARIABLES']['PAGE']=='teg')?$arParams["FILTER_NAME"]:null
		),
		$component);
	?>
