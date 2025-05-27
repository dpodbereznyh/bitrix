<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

class RandomProductComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['IBLOCK_ID'] = isset($arParams['IBLOCK_ID']) ? intval($arParams['IBLOCK_ID']) : 0;
        $arParams['SECTION_ID'] = isset($arParams['SECTION_ID']) ? intval($arParams['SECTION_ID']) : 0;
        $arParams['ITEM_COUNT'] = (isset($arParams['ITEM_COUNT']) && intval($arParams['ITEM_COUNT']) > 0) ? intval($arParams['ITEM_COUNT']) : 1;

        if ($arParams['ITEM_COUNT'] <= 0) {
            $arParams['ITEM_COUNT'] = 1;
        }

        return $arParams;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError('Модуль Инфоблоки не установлен');
            return;
        }

        if (!Loader::includeModule('catalog')) {
            ShowError('Модуль Торговый каталог не установлен');
            return;
        }

        if ($this->startResultCache(false, [$this->arParams['IBLOCK_ID'], $this->arParams['SECTION_ID'], $this->arParams['ITEM_COUNT']])) {
            $this->arResult = [];

            if ($this->arParams['IBLOCK_ID'] <= 0) {
                $this->AbortResultCache();
                ShowError('Не выбран инфоблок');
                return;
            }

            $this->getRandomProducts();

            $this->includeComponentTemplate();
        }
    }

    protected function getRandomProducts()
    {
        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'ACTIVE' => 'Y',
            'INCLUDE_SUBSECTIONS' => 'Y'
        ];

        if ($this->arParams['SECTION_ID'] > 0) {
            $filter['SECTION_ID'] = $this->arParams['SECTION_ID'];
        }

        $order = ['RAND' => 'ASC'];
        $select = ['ID', 'NAME', 'DETAIL_PAGE_URL'];

        $res = \CIBlockElement::GetList(
            $order,
            $filter,
            false,
            ['nTopCount' => $this->arParams['ITEM_COUNT']],
            $select
        );

        while ($item = $res->GetNext()) {
            $price = $this->getProductPrice($item['ID']);
            $this->arResult[] = [
                'ID' => $item['ID'],
                'NAME' => $item['NAME'],
                'DETAIL_PAGE_URL' => $item['DETAIL_PAGE_URL'],
                'PRICE' => $price,
            ];
        }
    }

    protected function getProductPrice($productId)
    {
        $price = 0;
        $priceData = \CCatalogProduct::GetOptimalPrice($productId);
        if ($priceData && isset($priceData['RESULT_PRICE']['BASE_PRICE'])) {
            $price = $priceData['RESULT_PRICE']['BASE_PRICE'];
        }
        return $price;
    }
}

