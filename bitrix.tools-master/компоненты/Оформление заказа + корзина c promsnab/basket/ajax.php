<?

require_once $_SERVER["DOCUMENT_ROOT"]
    . "/bitrix/modules/main/include/prolog_before.php";?>


    <? 

    use Bitrix\Main\Application;

    $req=Application::getInstance()->getContext()->getRequest();
    $APPLICATION->IncludeComponent(
    "forumedia:basket",
    "",
    Array(
        'LID'=>$req['LID'],
        'AJAX'=>'Y',
        'OUT_LID'=>!empty($req['OUT_LID'])?$req['OUT_LID']:'',
        'MIN_TOTAL'=>!empty(BASKET_MIN_TOTAL)?BASKET_MIN_TOTAL:''
    )
);?>



