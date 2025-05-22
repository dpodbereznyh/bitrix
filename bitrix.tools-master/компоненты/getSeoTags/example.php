<? $APPLICATION->IncludeComponent(
    "forumedia:getSeoTags",
    ".default",
    array(
        "CACHE_TIME" => "3600000",
        "CACHE_TYPE" => "A",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ELEMENT_ID" => "",
        "SECTION_ID" => $arSection["ID"],
        "MODE" => "SECT",
        "COMPONENT_TEMPLATE" => ".default"
    ),
    false
); ?>