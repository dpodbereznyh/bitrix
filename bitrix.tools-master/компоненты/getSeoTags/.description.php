<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("SEO_TAGS_COMPONENT_NAME"),
    "DESCRIPTION" => Loc::getMessage("SEO_TAGS_COMPONENT_DESCRIPTION"),
    "SORT" => 10,
    "PATH" => [
        "ID" => Loc::getMessage("SEO_TAGS_COMPONENT_PATH_ID"),
        "CHILD" => [
            "ID" => Loc::getMessage("SEO_TAGS_COMPONENT_PATH_CHILD_ID"),
            "NAME" => Loc::getMessage("SEO_TAGS_COMPONENT_PATH_CHILD_NAME"),
        ],
    ],
];
