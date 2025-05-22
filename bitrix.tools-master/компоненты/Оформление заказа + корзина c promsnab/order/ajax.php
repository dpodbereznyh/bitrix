<?

require_once $_SERVER["DOCUMENT_ROOT"]
    . "/bitrix/modules/main/include/prolog_before.php";?>


    <? 

    use Bitrix\Main\Application;

    $req=Application::getInstance()->getContext()->getRequest();
    $APPLICATION->IncludeComponent(
    "forumedia:order",
    "",
    Array(
        'LID'=>$req['LID'],
        'AJAX'=>'Y'
    )
);?>



