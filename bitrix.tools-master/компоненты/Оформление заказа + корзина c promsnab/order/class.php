<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Sale;



if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class OrderCompSimple extends CBitrixComponent
{
    private $order;
    public $basket;
    public $basketBase;
    public $shipmentCollection;
    public $paymentCollection;
    private $_request;

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

     public function getShipments()
    {
         $result = \Bitrix\Sale\Delivery\Services\Table::getList(array('order' => array('SORT' => 'ASC'),'filter' => array('ACTIVE'=>'Y')));
        while($delivery=$result->fetch())
        {
        $this->arResult['DELIVERY'][]=$delivery;
        }
    }

     public function getPayments()
    {
        $paySystemResult = \Bitrix\Sale\PaySystem\Manager::getList(array('order' => array('SORT' => 'ASC'),'filter'  => array('ACTIVE' => 'Y')));
        while ($paySystem = $paySystemResult->fetch())
        {
        $this->arResult['PAYMENT'][]=$paySystem;
        }
    }

     public function orderSave()
    {
        //$this->setOrderCurr();
        $this->order->doFinalAction(true);
        $result = $this->order->save();
        $this->arResult['ORDER_ID']=$this->order->getId();
    }


     public function getListBasket()
    {
        $price_id                = ($this->arParams['PRICE_ID']) ? $this->arParams['PRICE_ID'] : 1;
        $basket                  = $this->basketBase;
        $basketItems             = $basket->getOrderableItems();
        $this->arResult['BASKET']['ITEMS'] = [];
        if($basket->getBasePrice()!=0){
        $curr=$baseCurrency = \Bitrix\Currency\CurrencyManager::getBaseCurrency();
        $this->arResult['BASKET']['BASE_PRICE']           = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $basket->getBasePrice(), $curr);
        $this->arResult['BASKET']['PRICE']                = \Bitrix\Catalog\Product\Price::roundPrice($price_id, $basket->getPrice(), $curr);
        $this->arResult['BASKET']['PRINT_BASE_PRICE']     = CurrencyFormat($this->arResult['BASKET']['BASE_PRICE'], $curr);
        $this->arResult['BASKET']['PRINT_PRICE']          = CurrencyFormat($this->arResult['BASKET']['PRICE'], $curr);
        $this->arResult['BASKET']['PRINT_DISCOUNT_PRICE'] = CurrencyFormat($this->arResult['BASKET']['BASE_PRICE'] - $this->arResult['BASKET']['PRICE'], $curr);}
        $weight=0;
        foreach ($basketItems as &$item) {
            $itemArr['PRODUCT_ID'] = $item->getProductId(); // ID товара
           
            $itemArr['ID'] = $item->getId(); // ID записи в корзине

            $res = CIBlockElement::GetList(array(), ['ID' => $itemArr['PRODUCT_ID']]);
            $ob  = $res->GetNextElement();
            
            $result_cat = \Bitrix\Catalog\ProductTable::getList(array('filter' => array('=ID'=>$itemArr['PRODUCT_ID'])));
            if($product_prp=$result_cat->fetch()){$itemArr['PRODUCT']['CATALOG']=$product_prp;}
            if (!empty($ob)) {
                $itemArr['PRODUCT']['FIELDS']     = $ob->GetFields();
                $itemArr['PRODUCT']['PROPERTIES'] = $ob->GetProperties();
            }

            $itemArr['QUANTITY'] = $item->getQuantity(); // Количество
            $itemArr['WEIGHT']   = $itemArr['PRODUCT']['CATALOG']['WEIGHT'];
            $weight+=$itemArr['PRODUCT']['CATALOG']['WEIGHT']*$itemArr['QUANTITY'];
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

            $this->arResult['BASKET']['ITEMS'][$itemArr['ID']] = $itemArr;
            $curr                                    = $itemArr['PRICES']['CURRENCY'];
        } 
        $this->arResult['BASKET']['WEIGHT']=$weight;
    }

      public function setOrderCurr()
    {
      $this->getShipments();
      $this->getPayments(); 
    $paymentId=$this->_request['PAYMENT_ID']?$this->_request['PAYMENT_ID']:($this->arResult['PAYMENT'][0]['ID']?$this->arResult['PAYMENT'][0]['ID']:1);
    $shipmentId=$this->_request['SHIPMENT_ID']?$this->_request['SHIPMENT_ID']:$this->arResult['DELIVERY'][0]['ID'];
    $properties=$this->_request['PROPERTIES'];
    $properties_file=$_FILES;
    $personal_type_id=$this->_request['PERSONAL_TYPE_ID']?$this->_request['PERSONAL_TYPE_ID']:2;
    $this->order->setPersonTypeId($personal_type_id);
//var_dump($this->order);
//добавляем корзину
    $this->order->setBasket($this->basketBase);
    $this->order->refreshData();
    $basket=$this->order->getBasket();

//добавляем доставку
    $shipmentCollection=$this->order->getShipmentCollection();
    $shipment = $shipmentCollection->createItem();

    $service = \Bitrix\Sale\Delivery\Services\Manager::getById($shipmentId);
    $shipment->setFields(array(
    'DELIVERY_ID' => $service['ID'],
    'DELIVERY_NAME' => $service['NAME']));
    $shipmentItemCollection = $shipment->getShipmentItemCollection();
    foreach ($basket as $item)
    {
        $shipmentItem = $shipmentItemCollection->createItem($item);
        $shipmentItem->setQuantity($item->getQuantity());
    }
    $this->order->refreshData();

//добавляем оплату
    $paymentCollection = $this->order->getPaymentCollection();
    $payment = $paymentCollection->createItem();
    $paySystemService = \Bitrix\Sale\PaySystem\Manager::getObjectById($paymentId); //$paySystemId  - ИД платежной системы
    $payment->setFields(
        array('PAY_SYSTEM_ID' => $paymentId,
              'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
              'SUM'=> $this->order->getPrice())
            );

    $this->order->refreshData();

    $this->getProperties();
    $this->propertyCollection = $this->order->getPropertyCollection();
    foreach ($properties as $key=>$prop)
      {
        //var_dump([$key=>$this->arResult['PROPS'][$key]['ID']]);
        $property = $this->propertyCollection->getItemByOrderPropertyId($this->arResult['PROPS'][$key]['ID']);
        
        if(!empty($property)){
//array CFile::MakeFileArray()
            $property->setValue($prop);}   
      }

     //var_dump($properties_file);
     //var_dump($this->arResult['PROPS']);
       // die;
       if(!empty($properties_file)){
        $property = $this->propertyCollection->getItemByOrderPropertyId($this->arResult['PROPS'][array_keys($properties_file)[0]]['ID']);
        
        if(!empty($property)){
            $fileArr=current($properties_file);
            move_uploaded_file($fileArr['tmp_name'], $_SERVER["DOCUMENT_ROOT"].'/upload/zakaz_files/'.$fileArr['name']);
             $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/zakaz_files/'.$fileArr['name']);
             $file = CFile::SaveFile($arFile, 'vars');
          // var_dump($property);
          // var_dump($properties_file);
         //  die;
            $property->setValue($file);}   
     }

     if(!empty($this->_request['USER_DESCRIPTION'])) $this->order->setField('USER_DESCRIPTION', $this->_request['USER_DESCRIPTION']);
    }

    public function getProperties()
    {
      $this->propertyCollection = $this->order->getPropertyCollection();
      foreach ($this->propertyCollection as $property)
      {
        $prop=$property->getProperty();
        $code=$property->getField('CODE');
        $this->arResult['PROPS'][$code]=$prop;
        $this->arResult['PROPS'][$code]['VALUE']=$property->getValue();
        $this->arResult['PROPS'][$code]['~VALUE']=$property->getViewHtml();
      } 
      $this->arResult['USER_DESCRIPTION']=$this->order->getField('USER_DESCRIPTION'); 
    }

     public function getCurrDataOrder()
    {
       $this->getListBasket(); 
    }


    public function executeComponent()
    {

        $this->_checkModules();
//var_dump($_FILES);
//var_dump($_POST);
//die;
        $this->_request = Application::getInstance()->getContext()->getRequest();
        if(!empty($this->_request)){$_SESSION['formdata']=$this->_request;}
        if(empty($this->_request)&&!empty($_SESSION['formdata'])){$this->_request=$_SESSION['formdata'];}
        $this->basketBase = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $this->arParams['LID']);

        \Bitrix\Sale\DiscountCouponsManager::init();

        $siteId = Bitrix\Main\Context::getCurrent()->getSite();

        $this->order = \Bitrix\Sale\Order::create($siteId, \Bitrix\Sale\Fuser::getId());

        $this->setOrderCurr();

        if (!empty($this->_request['action'])) {
            $action = $this->_request['action'];
            $this->arResult['RESULT']=$this->$action();
        }

        $this->getCurrDataOrder();
 
        if (!empty($this->arParams['AJAX'])) 
        {

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($this->arResult, JSON_UNESCAPED_UNICODE);
            die;
        }

        $this->includeComponentTemplate();
    }
}
