//пример из polymermc /local/templates/.default/components/bitrix/catalog/catalog/section.php


//Получаем SEO раздела
$ipropSectionTemplates = new \Bitrix\Iblock\InheritedProperty\SectionTemplates($arParams["IBLOCK_ID"], $intSectionID);
$sectionTemplates = $ipropSectionTemplates->findTemplates();
// Получаем SEO шаблоны ИБ
$ipropIblockTemplates = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($arParams["IBLOCK_ID"]);
$iblockTemplates = $ipropIblockTemplates->findTemplates();

$arSeoFieldTemplates = ['SECTION_META_TITLE', 'SECTION_META_DESCRIPTION', 'SECTION_PAGE_TITLE'];
foreach ($arSeoFieldTemplates as $seoField) {
	if ($sectionTemplates[$seoField]["INHERITED"] == "Y") {
		//$sectionTemplates[$seoField]["TEMPLATE"] = $iblockTemplates[$seoField]["TEMPLATE"];
		$template = $iblockTemplates[$seoField]["TEMPLATE"];
		$entity = new \Bitrix\Iblock\Template\Entity\Section($intSectionID);

		$metaValue = Bitrix\Iblock\Template\Engine::process($entity, $template);

		switch ($seoField) {
			case "SECTION_META_TITLE":
				$GLOBALS['APPLICATION']->SetPageProperty("title", $metaValue);
				break;
			case "SECTION_META_DESCRIPTION":
				$GLOBALS['APPLICATION']->SetPageProperty("description", $metaValue);
				break;
			case "SECTION_PAGE_TITLE":
                if (!empty($APPLICATION->arAdditionalChain)) {
                    $lastIndex = array_key_last($APPLICATION->arAdditionalChain);
                    $APPLICATION->arAdditionalChain[$lastIndex]['TITLE'] = $metaValue;
                }

				$GLOBALS['APPLICATION']->SetTitle($metaValue);
				break;
		}
	}
}
?>