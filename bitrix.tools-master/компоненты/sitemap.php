<?
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php";
$APPLICATION->SetPageProperty("title", "Карта сайта");
$APPLICATION->SetPageProperty("description", "Карта сайта");
$APPLICATION->SetTitle("Карта сайта");

    $Link[]=['Главная'=>'/'];
    $Link[]=['О компании'=>'/company/'];
    $Link[]=['Реквизиты'=>'/company/rekvizity/'];
    $Link[]=['Акции'=>'/sale/'];
    $Link[]=['Помощь'=>'/help/'];
    $Link[]=['Условия оплаты'=>'/help/payment/'];
    $Link[]=['Условия доставки'=>'/help/delivery/'];
    $Link[]=['Гарантия на товар'=>'/help/warranty/'];
    $Link[]=['Контакты'=>'/contacts/'];
    $Link[]=['Политика конфиденциальности'=>'/help/police/'];
    $Link[]=['Пользовательское соглашение'=>'/help/pravilo/'];
    $Link[]=['Каталог'=>'/catalog/'];

$APPLICATION->IncludeComponent(
    "forumedia:sitemap", 
    "", 
    array(
    'LINK'=>$Link,
    'IBLOCK_CATALOG'=>[[76=>false]],
    'IBLOCK_TEG'=>[94],
    ));

?>
<?require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php";?>