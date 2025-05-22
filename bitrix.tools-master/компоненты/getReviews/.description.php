<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("REVIEW_COMPONENT_NAME"),
    "DESCRIPTION" => Loc::getMessage("REVIEW_COMPONENT_DESCRIPTION"),
    "SORT" => 10,
    "PATH" => [
        "ID" => Loc::getMessage("REVIEW_COMPONENT_PATH_ID"),
        "CHILD" => [
            "ID" => Loc::getMessage("REVIEW_COMPONENT_PATH_CHILD_ID"),
            "NAME" => Loc::getMessage("REVIEW_COMPONENT_PATH_CHILD_NAME"),
        ],
    ],
];
