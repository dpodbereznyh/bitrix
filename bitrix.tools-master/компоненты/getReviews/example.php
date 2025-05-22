<? $APPLICATION->IncludeComponent(
    "forumedia:getReviews",
    "",
    array(
        "CACHE_TIME" => "3600000",
        "CACHE_TYPE" => "A",
        "PAGE_SIZE" => "30"
    )
);
