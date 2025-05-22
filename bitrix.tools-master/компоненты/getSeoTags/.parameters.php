<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "PARAMETERS" => [
        "CACHE_TYPE" => ["DEFAULT" => "A"],
        "CACHE_TIME"  =>  ["DEFAULT" => 3600000],
        "IBLOCK_ID" => [
            "NAME" => Loc::getMessage("SEO_TAGS_PARAMETER_IBLOCK_ID_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "ELEMENT_ID" => [
            "NAME" => Loc::getMessage("SEO_TAGS_PARAMETER_ELEMENT_ID_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "SECTION_ID" => [
            "NAME" => Loc::getMessage("SEO_TAGS_PARAMETER_SECTION_ID_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "MODE" => [
            "NAME" => Loc::getMessage("SEO_TAGS_PARAMETER_MODE_NAME"),
            "TYPE" => "LIST",
            "VALUES" => [
                "ELEM" => Loc::getMessage("SEO_TAGS_PARAMETER_MODE_VALUE_1"),
                "SECT" => Loc::getMessage("SEO_TAGS_PARAMETER_MODE_VALUE_2"),
            ]
        ],
    ],
];
