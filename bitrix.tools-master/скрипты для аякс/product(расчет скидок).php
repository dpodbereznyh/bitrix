<?require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";?>

<?
$use_quantity=false;
/***Как посчитать стоимость товара или предложения со всеми скидками***/
function getFinalPriceInCurrency($item_id, $sale_currency = 'RUB',$quantity) {
CModule::IncludeModule("iblock"); 
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");   
    global $USER;

    $res = CIBlockElement::GetList([], ['ID'=>$item_id], false, false, ["ID", "IBLOCK_ID", "NAME", "CATALOG_QUANTITY"]);
    $ob=$res->GetNext();
    if(!empty($ob)&&$use_quantity&&$ob["CATALOG_QUANTITY"]<$quantity)
    {
      return false;
    }elseif(empty($ob)){
      return false;
    }


    $currency_code = 'RUB';

    // Простой товар, без торговых предложений (для количества равному 1)
    $price = CCatalogProduct::GetOptimalPrice($item_id, 1, $USER->GetUserGroupArray(), 'N');

    // Получили цену?
    if(!$price || !isset($price['PRICE'])) {
        return false;
    }

    // Меняем код валюты, если нашли
    if(isset($price['CURRENCY'])) {
        $currency_code = $price['CURRENCY'];
    }
    if(isset($price['PRICE']['CURRENCY'])) {
        $currency_code = $price['PRICE']['CURRENCY'];
    }

    // Получаем итоговую цену
    $price_base = $price['PRICE']['PRICE'];
$final_price=$price_base;
    // Ищем скидки и пересчитываем цену товара с их учетом
    $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
    if(is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
        $final_price = CCatalogProduct::CountPriceWithDiscount($price_base, $currency_code, $arDiscounts);
    }

    // Если необходимо, конвертируем в нужную валюту
    if($currency_code != $sale_currency) {
        $final_price = CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
    }

 $price_id=1;
 $curr= \Bitrix\Currency\CurrencyManager::getBaseCurrency();
 $final_price           = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $final_price*$quantity, $curr);
 $price_base               = \Bitrix\Catalog\Product\Price::roundPrice($price_id,  $price_base*$quantity, $curr);
 $final_price_print     = CurrencyFormat($final_price, $curr);
 $price_base_print = CurrencyFormat($price_base, $curr);

    return [
    'BASE_PRICE'=>$price_base,
    'DISCOUNT_PRICE'=>$final_price,
    'PRINT_BASE_PRICE'=>$price_base_print,
    'PRINT_DISCOUNT_PRICE'=>$final_price_print];

}

if(!empty($_REQUEST['ID'])&&(isset($_REQUEST['QUANTITY']))){
//var_dump(expression)
 header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(getFinalPriceInCurrency($_REQUEST['ID'], 'RUB',$_REQUEST['QUANTITY']), JSON_UNESCAPED_UNICODE);
            die;
}
