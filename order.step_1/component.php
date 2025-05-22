<?

  use Bitrix\Iblock\ElementTable;
  use Bitrix\Main\Loader;

  if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

  Loader::includeModule("catalog");
  Loader::includeModule("sale");

  global $USER;

  $userGroups = $USER->GetUserGroupArray();

  // Получаем список товаров в корзине
  $basketItems = CSaleBasket::GetList(
    [],
    [
      "FUSER_ID" => CSaleBasket::GetBasketUserID(),
      "LID" => SITE_ID
    ]
  );

  $arBasketItems = [];
  $orderPrice = 0;
  $orderWeight = 0;
  $orderDiscount = 0;

  while ($arBasketItem = $basketItems->Fetch()) {

    // Получаем ID картинки
    $arProduct = ElementTable::getList([
      "filter" => [
        "ID" => $arBasketItem["PRODUCT_ID"]
      ],
      "select" => [
        "ID",
        "NAME",
        "PREVIEW_PICTURE",
      ],
    ])->fetch();

    // Цена
    $optimalPrice = CCatalogProduct::GetOptimalPrice(
      $arBasketItem["PRODUCT_ID"],
      $arBasketItem["QUANTITY"],
      $userGroups,
      "Y"
    );

    $orderPrice = $orderPrice + $optimalPrice["RESULT_PRICE"]["DISCOUNT_PRICE"];
    $orderWeight = $orderWeight + $arBasketItem["WEIGHT"];
    $orderDiscount = $orderDiscount + $optimalPrice["RESULT_PRICE"]["DISCOUNT"];

    // Формируем массив с данными о товарах
    $arBasketItems[] = [
      "ID" => $arBasketItem["PRODUCT_ID"],
      "NAME" => $arProduct["NAME"],
      "PRICE" => $arBasketItem["PRICE"],
      "OPTIMAL_PRICE" => $optimalPrice,
      "WEIGHT" => $arBasketItem["WEIGHT"],
      "QUANTITY" => $arBasketItem["QUANTITY"],
      "PREVIEW_PICTURE_ID" => $arProduct["PREVIEW_PICTURE"] ?? "",
    ];

  }

  $arResult["BASKET_ITEMS"] = $arBasketItems;
  $arResult["ORDER_PRICE"] = $orderPrice;
  $arResult["ORDER_WEIGHT"] = $orderWeight;
  $arResult["ORDER_DISCOUNT"] = $orderDiscount;

  $this->IncludeComponentTemplate();