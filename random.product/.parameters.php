<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Iblock;

$arComponentParameters = [];

if (!Loader::includeModule('iblock')) {
    return;
}

$arIBlocks = [];
$rsIBlocks = CIBlock::GetList(
    ['SORT' => 'ASC'],
    ['TYPE' => 'catalog', 'ACTIVE' => 'Y']
);

while ($iblock = $rsIBlocks->Fetch()) {
    $arIBlocks[$iblock['ID']] = '[' . $iblock['ID'] . '] ' . $iblock['NAME'];
}

$arSections = [];
$selectedIblock = isset($arCurrentValues['IBLOCK_ID']) ? intval($arCurrentValues['IBLOCK_ID']) : 0;

if ($selectedIblock > 0) {
    $rsSections = CIBlockSection::GetList(
        ['NAME' => 'ASC'],
        ['IBLOCK_ID' => $selectedIblock, 'GLOBAL_ACTIVE' => 'Y'],
        false,
        ['ID', 'NAME']
    );

    while ($section = $rsSections->Fetch()) {
        $arSections[$section['ID']] = $section['NAME'];
    }
}

$arComponentParameters = [
    'GROUPS' => [],
    'PARAMETERS' => [
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'DEFAULT' => '2',
            'REFRESH' => 'Y'
        ],
        'SECTION_ID' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('SECTION_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $arSections,
            'DEFAULT' => '9',
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
        ],
        'ITEM_COUNT' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('ITEM_COUNT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '1',
        ],
        'CACHE_TIME' => [
            'DEFAULT' => 3600,
            'NAME' => GetMessage('CACHE_TIME'),
        ],
    ],
];
