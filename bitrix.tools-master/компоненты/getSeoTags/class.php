<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class seoTags extends CBitrixComponent
{
    protected function getRegionUfProperty($iblockId = "", $sectionId = "")
    {
        $arFilter = array('IBLOCK_ID' => $iblockId, 'ID' => $sectionId);
        $arSelect = array('IBLOCK_ID', 'ID', 'NAME', 'UF_*');
        $res = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter, false, $arSelect);
        while ($arField = $res->GetNext()) {
            $arSection[] = $arField;
        }
        return $arSection[0];
    }

    protected function getSeoSection($iblockId = "", $sectionId = "", $iblockTemplates = [], $arRegion = [])
    {
        global $APPLICATION;

        // Получаем SEO шаблоны раздела
        $ipropSectionTemplates = new \Bitrix\Iblock\InheritedProperty\SectionTemplates($iblockId, $sectionId);
        $sectionTemplates = $ipropSectionTemplates->findTemplates();
        $arSeoFieldTemplates = ['SECTION_META_TITLE', 'SECTION_META_DESCRIPTION', 'SECTION_PAGE_TITLE'];
        $arSection = $this->getRegionUfProperty($iblockId, $sectionId);
        foreach ($arSeoFieldTemplates as $seoField) {
            $template = $iblockTemplates[$seoField]["TEMPLATE"];
            $entity = new \Bitrix\Iblock\Template\Entity\Section($sectionId);
            $metaValue = Bitrix\Iblock\Template\Engine::process($entity, $template);
            if (empty($arRegion["UF_URL"])) {
                // SEO основной сайт
                if ($sectionTemplates[$seoField]["INHERITED"] == "Y") {
                    switch ($seoField) {
                        case "SECTION_META_TITLE":
                            $GLOBALS["SEO_TITLE_IS_TEMPLATE"] = "Y";
                            $GLOBALS['APPLICATION']->SetPageProperty("title", $metaValue);
                            break;
                        case "SECTION_META_DESCRIPTION":
                            $arDesc = explode('.', $metaValue);
                            $prefix = array_shift($arDesc);
                            $metaValue = $prefix . " в Кирове." . implode('.', $arDesc);
                            $GLOBALS['APPLICATION']->SetPageProperty("description", $metaValue);
                            break;
                        case "SECTION_PAGE_TITLE":
                            $metaValue .= " в Кирове";
                            if (!empty($APPLICATION->arAdditionalChain)) {
                                $lastIndex = array_key_last($APPLICATION->arAdditionalChain);
                                $APPLICATION->arAdditionalChain[$lastIndex]['TITLE'] = $metaValue;
                            }
                            $GLOBALS['APPLICATION']->SetTitle($metaValue);
                            break;
                    }
                }
            } else {
                // SEO регионы
                switch ($seoField) {
                    case "SECTION_META_TITLE":
                        if ($title = $arSection['UF_TITLE_' . mb_strtoupper($arRegion["UF_URL"])]) {
                            $GLOBALS["SEO_TITLE_REGION_IS_CUSTOM"] = "Y";
                            $APPLICATION->SetPageProperty("title",  $title);
                        } else {
                            $GLOBALS['APPLICATION']->SetPageProperty("title", $metaValue);
                        }
                        break;
                    case "SECTION_META_DESCRIPTION":
                        if ($desc = $arSection['UF_DESC_' . mb_strtoupper($arRegion["UF_URL"])]) {
                            $APPLICATION->SetPageProperty("description",  $desc);
                        } else {
                            $arDesc = explode('.', $metaValue);
                            $prefix = array_shift($arDesc);
                            $metaValue = $prefix . " " . $arRegion['UF_META'] . "." . implode('.', $arDesc);
                            $GLOBALS['APPLICATION']->SetPageProperty("description", $metaValue);
                        }
                        break;
                    case "SECTION_PAGE_TITLE":
                        if ($h1 = $arSection['UF_H_' . mb_strtoupper($arRegion["UF_URL"])]) {
                            $APPLICATION->SetTitle($h1);
                        } else {
                            $metaValue .= " " . $arRegion["UF_META"];
                            if (!empty($APPLICATION->arAdditionalChain)) {
                                $lastIndex = array_key_last($APPLICATION->arAdditionalChain);
                                $APPLICATION->arAdditionalChain[$lastIndex]['TITLE'] = $metaValue;
                            }
                            $GLOBALS['APPLICATION']->SetTitle($metaValue);
                        }
                        break;
                }
            }
        }
    }

    protected function getSeoElement($iblockId = "", $elementId = "", $iblockTemplates = [], $arRegion = [])
    {
        global $APPLICATION;

        // Получаем SEO шаблоны элемента
        $ipropElementTemplates = new \Bitrix\Iblock\InheritedProperty\ElementTemplates($iblockId, $elementId);
        $elementTemplates = $ipropElementTemplates->findTemplates();
        $arSeoFieldTemplates = ['ELEMENT_META_TITLE', 'ELEMENT_META_DESCRIPTION', 'ELEMENT_PAGE_TITLE'];
        foreach ($arSeoFieldTemplates as $seoField) {
            if ($elementTemplates[$seoField]["INHERITED"] == "Y") {
                $template = $iblockTemplates[$seoField]["TEMPLATE"];
                $entity = new \Bitrix\Iblock\Template\Entity\Element($elementId);
                $metaValue = Bitrix\Iblock\Template\Engine::process($entity, $template);
                switch ($seoField) {
                    case "ELEMENT_META_TITLE":
                        $GLOBALS["SEO_TITLE_IS_TEMPLATE"] = "Y";
                        $GLOBALS['APPLICATION']->SetPageProperty("title", $metaValue);
                        break;
                    case "ELEMENT_META_DESCRIPTION":
                        $GLOBALS['APPLICATION']->SetPageProperty("description", $metaValue);
                        break;
                    case "ELEMENT_PAGE_TITLE":
                        if (!empty($APPLICATION->arAdditionalChain)) {
                            $lastIndex = array_key_last($APPLICATION->arAdditionalChain);
                            $APPLICATION->arAdditionalChain[$lastIndex]['TITLE'] = $metaValue;
                        }
                        $GLOBALS['APPLICATION']->SetTitle($metaValue);
                        break;
                }
            }
        }
    }

    protected function getSeoTags($iblockId = "", $elementId = "", $sectionId = "", $mode = "SECT")
    {
        global $APPLICATION;

        // Получаем регионы
        $region = new regionDomainManager;
        $arRegion = $region->region();

        // Получаем SEO шаблоны ИБ
        $ipropIblockTemplates = new \Bitrix\Iblock\InheritedProperty\IblockTemplates($iblockId);
        $iblockTemplates = $ipropIblockTemplates->findTemplates();

        switch ($mode) {
            case "SECT":
                $this->getSeoSection($iblockId, $sectionId, $iblockTemplates, $arRegion);
                break;
            case "ELEM":
                $this->getSeoElement($iblockId, $elementId, $iblockTemplates, $arRegion);
                break;
        }

        return;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule("iblock")) {
            $this->AbortResultCache();
            throw new \Exception('Не загружен модуль iblock');
        }

        if ($this->StartResultCache()) {
            $this->getSeoTags($this->arParams["IBLOCK_ID"], $this->arParams["ELEMENT_ID"], $this->arParams["SECTION_ID"], $this->arParams["MODE"]);
        }
    }
}
