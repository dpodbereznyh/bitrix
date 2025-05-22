<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Sale;



if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class ExampleCompSimple extends CBitrixComponent
{
    private $_request;
    public $basketBase;
    public $basketOut;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules()
    {
        if (!Loader::includeModule('iblock')
            || !Loader::includeModule('sale')
        ) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllMain|CMain
     */
    private function _app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllUser|CUser
     */
    private function _user()
    {
        global $USER;
        return $USER;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    public function getList()
    {
        $price_id                = ($this->arParams['PRICE_ID']) ? $this->arParams['PRICE_ID'] : 1;
        $basket                  = (!empty($this->arParams['OUT_LID'])) ? $this->basketOut : $this->basketBase;
        $basketItems             = $basket->getOrderableItems();
        $this->arResult['ITEMS'] = [];
if($basket->getBasePrice()!=0){
$curr=$baseCurrency = \Bitrix\Currency\CurrencyManager::getBaseCurrency();
        $this->arResult['BASE_PRICE']           = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $basket->getBasePrice(), $curr);
        $this->arResult['PRICE']                = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $basket->getPrice(), $curr);
        $this->arResult['PRINT_BASE_PRICE']     = CurrencyFormat($this->arResult['BASE_PRICE'], $curr);
        $this->arResult['PRINT_PRICE']          = CurrencyFormat($this->arResult['PRICE'], $curr);
        $this->arResult['PRINT_DISCOUNT_PRICE'] = CurrencyFormat($this->arResult['BASE_PRICE'] - $this->arResult['PRICE'], $curr);}

        foreach ($basketItems as &$item) {
            $itemArr['PRODUCT_ID'] = $item->getProductId(); // ID товара
            if (!empty($this->arParams['OUT_LID'])) {
                $res = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
                    'filter'                                           => array(
                        'PRODUCT_ID' => $itemArr['PRODUCT_ID'],
                        'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                        'ORDER_ID'   => null,
                        'LID'        => $this->arParams['LID'],
                    ),
                ))->fetch();
                $itemArr['ID'] = $res['ID'];
            } else {
                $itemArr['ID'] = $item->getId();} // ID записи в корзине

            $res = CIBlockElement::GetList(array(), ['ID' => $itemArr['PRODUCT_ID']]);
            $ob  = $res->GetNextElement();
            if (!empty($ob)) {
                $itemArr['PRODUCT']['FIELDS']     = $ob->GetFields();
                $itemArr['PRODUCT']['PROPERTIES'] = $ob->GetProperties();
            }
            $itemArr['QUANTITY'] = $item->getQuantity(); // Количество
            $itemArr['WEIGHT']   = $item->getWeight();

            $itemArr['PROPERTIES'] = $item->getPropertyCollection()->getPropertyValues();
            $itemArr['NAME']       = $item->getField('NAME');
            //\Bitrix\Catalog\Product\Price::roundPrice(
            $itemArr['PRICES']['CURRENCY']       = $item->getCurrency();
            $itemArr['PRICES']['PRICE']          = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getPrice(), $itemArr['PRICES']['CURRENCY']); // Цена за единицу
            $itemArr['PRICES']['FINAL_PRICE']    = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getFinalPrice(), $itemArr['PRICES']['CURRENCY']); // Сумма
            $itemArr['PRICES']['BASE_PRICE']     = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getBasePrice(), $itemArr['PRICES']['CURRENCY']); // цена без учета скидок
            $itemArr['PRICES']['DEFAULT_PRICE']  = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getDefaultPrice(), $itemArr['PRICES']['CURRENCY']); // цена по умолчанию
            $itemArr['PRICES']['DISCOUNT_PRICE'] = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getDiscountPrice(), $itemArr['PRICES']['CURRENCY']); // величина скидки
            $itemArr['PRICES']['VAT_RATE']       = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getVatRate(), $itemArr['PRICES']['CURRENCY']); // ставка ндс
            //$itemArr['PRICES']['PRICE_VAT']      = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getPriceWithVat(), $itemArr['PRICES']['CURRENCY']); // цена с ндс
           // $itemArr['PRICES']['BASE_PRICE_VAT'] = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getBasePriceWithVat(), $itemArr['PRICES']['CURRENCY']); // базовая цена с ндс
            $itemArr['PRICES']['INITIAL_PRICE']  = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $item->getInitialPrice(), $itemArr['PRICES']['CURRENCY']); // исходная цена без ндс
            $itemArr['PRICES']['VAT']            = $item->getVat(); // ндс
            $itemArr['PRICES']['PRICE_VAT']      = $item->isVatInPrice(); // цена включает ндс

            $itemArr['PRICES']['PRINT_PRICE']          = CurrencyFormat($itemArr['PRICES']['PRICE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_FINAL_PRICE']    = CurrencyFormat($itemArr['PRICES']['FINAL_PRICE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_BASE_PRICE']     = CurrencyFormat($itemArr['PRICES']['BASE_PRICE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_DEFAULT_PRICE']  = CurrencyFormat($itemArr['PRICES']['DEFAULT_PRICE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_DISCOUNT_PRICE'] = CurrencyFormat($itemArr['PRICES']['DISCOUNT_PRICE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_VAT_RATE']       = CurrencyFormat($itemArr['PRICES']['VAT_RATE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_PRICE_VAT']      = CurrencyFormat($itemArr['PRICES']['PRICE_VAT'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_BASE_PRICE_VAT'] = CurrencyFormat($itemArr['PRICES']['BASE_PRICE_VAT'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['PRINT_INITIAL_PRICE']  = CurrencyFormat($itemArr['PRICES']['INITIAL_PRICE'], $itemArr['PRICES']['CURRENCY']);
            $itemArr['PRICES']['CUSTOM_PRICE']         = $item->getField('CUSTOM_PRICE');

            $this->arResult['ITEMS'][$itemArr['ID']] = $itemArr;
            $curr                                    = $itemArr['PRICES']['CURRENCY'];
        }
       
    }

    public function removeFromBasket($lid = false)
    {
        
        $id     = $this->_request["ID"];
        if (is_array($id)) {
            foreach ($id as $idItem) {
                $item=$this->basketBase->getItemById($idItem);
                if(!empty($item)){$item->delete();
                $item->save();}
            }
        } elseif (isset($id)) {
            $item=$this->basketBase->getItemById($id);
            if(!empty($item)){$item->delete();
            $item->save();}
        }
        
        $this->basketBase->refresh();
        $this->basketBase->save();
    }

    public function clearBasketOut()
    {
        $basketItems = $this->basketOut->getBasketItems();
        foreach ($basketItems as &$item) {

            if(empty($this->arResult['ITEMS'][$item->getId()])){
            $item->delete();
            $item->save();
             } 
        }
        $this->basketOut->refresh();
        $this->basketOut->save();
    }

    public function clearBasket()
    {
        $basketItems = $this->basketBase->getBasketItems();
        foreach ($basketItems as &$item) {
            $item->delete();
            $item->save();
        }
        
        $this->basketBase->refresh();
        $this->basketBase->save();
    }

    public function setCountBasket($lid = false)
    {
        $id       = $this->_request["ID"];
        $item     = $this->basketBase->getItemById($id);
        $quantity = $item->getQuantity();
        if (isset($this->_request["MINUS"])) {--$quantity;}
        if (isset($this->_request["PLUS"])) {++$quantity;}
        if (isset($this->_request["QUANTITY"])) {$quantity = $this->_request["QUANTITY"];}
        if ($quantity > 0) {
            $item->setField('QUANTITY', $quantity);
        } else {
            $item->delete();
        }
        $this->basketBase->save();
    }

    public function addCoupon($lid = false)
    {
        $coupon = $this->_request["COUPON"];
        \Bitrix\Sale\DiscountCouponsManager::add($coupon);
        $oBasket    = $this->basketOut;
        $oDiscounts = \Bitrix\Sale\Discount::loadByBasket($oBasket);
        $oBasket->refreshData(['PRICE', 'COUPONS']);
        // пересчёт скидок для корзины
        $oDiscounts->calculate();
        $oBasket->refresh();
        $oBasket->save();
    }

    public function giftsBasket($lid = false)
    {
        if (empty($this->arParams['OUT_LID'])) {return;}
        $giftManager = \Bitrix\Sale\Discount\Gift\Manager::getInstance();
        $giftManager->setUserId(\Bitrix\Sale\Fuser::getId());
        \Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();
        $collections = $giftManager->getCollectionsByBasket($this->basketOut);
        \Bitrix\Sale\Compatible\DiscountCompatibility::revertUsageCompatible();
        $this->arResult['GIFTS'] = [];
        foreach ($collections as $collection) {
            foreach ($collection as $gift) {
                $giftProductId = $gift->getProductId();
                $res           = CIBlockElement::GetList(array(), ['ID' => $giftProductId]);
                $ob            = $res->GetNextElement();
                if (!empty($ob)) {
                    $itemArr['PRODUCT']['FIELDS']     = $ob->GetFields();
                    $itemArr['PRODUCT']['PROPERTIES'] = $ob->GetProperties();
                    $this->arResult['GIFTS'][]        = $itemArr;
                }
            }}
    }

    public function getCheckBasket()
    {
        $ids        = $this->_request['ID'];
        $this->clearBasketOut();

        foreach ($ids as $id) {
            $item = $this->basketBase->getItemById($id);

            $itemOrder = $this->basketOut->createItem('catalog', $item->getProductId());

            $fields = [
                'PRODUCT_ID'             => $item->getProductId(), // ID товара, обязательно
                'QUANTITY'               => $item->getQuantity(), // количество, обязательно
                'NAME'                   => $item->getField('NAME'),
                'LID'                    => $this->arParams['OUT_LID'],
                'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
            ];

            $basketPropertyCollection = $item->getPropertyCollection();
            $props                    = $basketPropertyCollection->getPropertyValues();

            foreach ($props as $prop) {
                $fields['PROPS'][] = ['NAME' => $prop['NAME'], 'CODE' => $prop['CODE'], 'VALUE' => $prop['VALUE']];
            }

            $itemOrder->setFields($fields);
            $itemOrder->save();

        }

        $this->basketOut->refresh();
        $this->basketOut->save();
        $this->giftsBasket();
        $this->addGift();
        if(!empty($this->arParams['MIN_TOTAL']))
        {
            $this->getList();
        if($this->basketOut->getPrice()>=$this->arParams['MIN_TOTAL'])
            {}
            else{
            $this->clearBasketOut();
            $this->arResult['MESSAGE']='Сумма товаров в корзине должна быть не менее <span class="popup-price">'.($this->arParams['MIN_TOTAL']).' руб</span>.';
            return false;
        }}
        return true;
    }

    public function getBasketAll()
    {
        $this->getBasket();
        $this->giftsBasket();
        $this->addGift();
        if(!empty($this->arParams['MIN_TOTAL']))
        {
            
        if($this->basketOut->getPrice()>=$this->arParams['MIN_TOTAL']){}
            else{
            $this->clearBasketOut();
            $this->arResult['MESSAGE']='Сумма товаров в корзине должна быть не менее <span class="popup-price">'.($this->arParams['MIN_TOTAL']).' руб</span>.';
            return false;
        }}
        return true;
    }

     public function getCheckFavorite()
    {
        $ids        = $this->_request['ID'];
        $this->clearBasketOut();

        foreach ($ids as $id) {
            $item = $this->basketBase->getItemById($id);

            $itemOrder = $this->basketOut->createItem('catalog', $item->getProductId());

            $fields = [
                'PRODUCT_ID'             => $item->getProductId(), // ID товара, обязательно
                'QUANTITY'               => '1', // количество, обязательно
                'NAME'                   => $item->getField('NAME'),
                'LID'                    => $this->arParams['OUT_LID'],
                'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
            ];

            $basketPropertyCollection = $item->getPropertyCollection();
            $props                    = $basketPropertyCollection->getPropertyValues();

            foreach ($props as $prop) {
                $fields['PROPS'][] = ['NAME' => $prop['NAME'], 'CODE' => $prop['CODE'], 'VALUE' => $prop['VALUE']];
            }

            $itemOrder->setFields($fields);
             $itemOrder->save();

        }
         
         $this->basketOut->refresh();
        $this->basketOut->save();
        $this->giftsBasket();
        $this->addGift();
        if(!empty($this->arParams['MIN_TOTAL']))
        {
            $this->getList();
        if($this->basketOut->getPrice()>=$this->arParams['MIN_TOTAL'])
            {}
            else{
            $this->clearBasketOut();
            $this->arResult['MESSAGE']='Сумма товаров в корзине должна быть не менее <span class="popup-price">'.($this->arParams['MIN_TOTAL']).' руб</span>.';
            return false;
        }}
        return true;
    }

    public function getBasket()
    {
        //$items2     = $this->basketOut->getOrderableItems();
        $this->clearBasketOut();
        $items = $this->basketBase->getBasketItems();
        foreach ($items as $item) {

            $itemOrder = $this->basketOut->createItem('catalog', $item->getProductId());

            $fields = [
                'PRODUCT_ID'             => $item->getProductId(), // ID товара, обязательно
                'QUANTITY'               => $item->getQuantity(), // количество, обязательно
                'NAME'                   => $item->getField('NAME'),
                'LID'                    => $this->arParams['OUT_LID'],
                'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
            ];

            $basketPropertyCollection = $item->getPropertyCollection();
            $props                    = $basketPropertyCollection->getPropertyValues();

            foreach ($props as $prop) {
                $fields['PROPS'][] = ['NAME' => $prop['NAME'], 'CODE' => $prop['CODE'], 'VALUE' => $prop['VALUE']];
            }

            $itemOrder->setFields($fields);

        }

        $this->basketOut->refresh();
        $this->basketOut->save();
    }

    public function addGift()
    {// var_dump($this->basketOut->getQuantityList());
        if (!empty($this->arResult['GIFTS'])) {
            foreach ($this->arResult['GIFTS'] as $gift) {
                $itemOrder = $this->basketOut->createItem('catalog', $gift['PRODUCT']['FIELDS']['ID']);

                $fields = [
                    'PRODUCT_ID'             => $gift['PRODUCT']['FIELDS']['ID'], // ID товара, обязательно
                    'QUANTITY'               => 1, // количество, обязательно
                    'NAME'                   => $gift['PRODUCT']['FIELDS']['NAME'],
                    'LID'                    => $this->arParams['OUT_LID'],
                    'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
                ];
                $itemOrder->setFields($fields);
            }
            $this->basketOut->save();
        }

    }

    public function addFaforiteBasket()
    {
        $ids        = $this->_request['ID'];
        $lid        = $this->_request['LID_FAV'];
        $basketView = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $lid);

        foreach ($ids as $id) {

            $itemBase = $this->basketBase->getItemById($id);

            $res = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
                'filter'                                           => array(
                    'PRODUCT_ID' => $itemBase->getProductId(),
                    'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                    'ORDER_ID'   => null,
                    'LID'        => $lid,

                ),
            ))->fetch();

            if (empty($res['ID'])) {

                $item = $basketView->createItem('catalog', $itemBase->getProductId());
                
                $fields = [
                    'PRODUCT_ID' => $itemBase->getProductId(), // ID товара, обязательно
                    'QUANTITY'   => $itemBase->getQuantity(), // количество, обязательно
                    'NAME'       => $itemBase->getField('NAME'),
                    'LID'        => $lid,
                    'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
                ];

                $basketPropertyCollection = $itemBase->getPropertyCollection();
                $props                    = $basketPropertyCollection->getPropertyValues();

                foreach ($props as $prop) {
                    $fields['PROPS'][] = ['NAME' => $prop['NAME'], 'CODE' => $prop['CODE'], 'VALUE' => $prop['VALUE']];
                }

                $item->setFields($fields);

            }}

        $basketView->save();

    }

    function printTimeLoad() {
        $newTime = microtime(true);
        ?><pre><?print_r($newTime - $this->time)?></pre><?
        
        $this->time = $newTime;
    }
    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent()
    {

        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();

        $this->basketBase = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $this->arParams['LID']);

        if (!empty($this->arParams['OUT_LID'])) 
        {
           
            $this->basketOut = \Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), $this->arParams['OUT_LID']);
            $this->clearBasketOut();
        }

        if (!empty($this->_request['action'])) {
            $action = $this->_request['action'];
            $this->arResult['RESULT']=$this->$action();
            if(in_array($this->_request['action'], ['getCheckBasket','getCheckFavorite','getBasketAll']))
             {
          }
        }

        if(!in_array($this->_request['action'], ['getCheckBasket','getCheckFavorite','getBasketAll']))
        {

        if (!empty($this->arParams['OUT_LID'])) 
        {
            $this->getBasket();
        }

        $this->giftsBasket();
        $this->addGift();

        $this->getList();

        if (!empty($this->arParams['OUT_LID'])) {$this->clearBasketOut();}
         }
 
        if (!empty($this->arParams['AJAX'])) {

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($this->arResult, JSON_UNESCAPED_UNICODE);
            die;
        }

        $this->includeComponentTemplate();
    }
}
