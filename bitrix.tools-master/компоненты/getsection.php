<? $APPLICATION->IncludeComponent(
                        "forumedia:getsection",
                        "",
                        [
                            'IBLOCK_ID'=>(LANGUAGE_ID == 'en')?7:2,
                            'FILTER'=>['ACTIVE'=>'Y'],
                            'SELECT'=>['IBLOCK_ID','DEPTH_LEVEL','NAME','SECTION_PAGE_URL','IBLOCK_SECTION_ID','PICTURE'],
                            'GROUP'=>true
                        ],
                        false
                    ); ?>

