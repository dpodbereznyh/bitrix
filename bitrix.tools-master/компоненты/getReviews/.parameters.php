<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "PARAMETERS" => [
        "CACHE_TYPE" => ["DEFAULT" => "A"],
        "CACHE_TIME"  =>  ["DEFAULT" => 3600000],
        "PAGE_SIZE" => [
            "NAME" => "Количество отзывов на странице",
            "DEFAULT" => 30
        ]
    ],
];
