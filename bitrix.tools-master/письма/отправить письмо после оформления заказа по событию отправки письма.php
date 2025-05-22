<?
\Bitrix\Main\EventManager::getInstance()->addEventHandler("sale", "OnOrderNewSendEmail", "ModifyOrderSaleMails");

function ModifyOrderSaleMails($orderID, &$eventName, &$arFields)
{  
    CModule::IncludeModule("iblock");
    CModule::IncludeModule("sale");
    CModule::IncludeModule("main");
   $order = Bitrix\Sale\Order::load($orderID);
 
   $basketOrder=$order->getBasket();
   $basketItems = $basketOrder->getBasketItems();
   $orderId=$order->getId();
   $textBasket="";
   $textBasketHTML="";
   $weight=0;
   foreach ($basketItems as $key => $item) 
    {
    $pid=$item->getProductId();  
    $price=$item->getPrice();
    $quantity=$item->getQuantity();
    $weight+=$item->getWeight()*$quantity;
    $name=$item->getField('NAME');
    $textBasket.=$name.' - '.$price.' руб. x '.$quantity." шт. \n";
    $textBasketHTML.=$name.' - '.$price.' руб. x '.$quantity." шт.<br/>";
    }

   $result=[];
   $collection = $order->getPropertyCollection();
   $props='';
      foreach ($collection as $property)
      {
        $prop=$property->getProperty();
        $code=$property->getField('CODE');
        $value='';
        $name='';
        $valueHtml='';
        if($code=='FILE')
        {
         $name="файл";
         $valueArr=$property->getValue();
         if(!empty($valueArr)){
         $value='https://'.$_SERVER['SERVER_NAME'].$valueArr['SRC'];
         $valueHtml='<a href='.$value.' download="'.$valueArr['ORIGINAL_NAME'].'">'.$valueArr['ORIGINAL_NAME'].'</a>';
         $result['PROP_FILE_HTML']=$valueHtml;
         $result['PROP_FILE']=$value;
        }}
        else
        {
        $name=$property->getName();
        $value=$property->getValue();
        $valueHtml=$property->getViewHtml();
          }
         if(!empty($value))
            {
            $props.=$name.': '.$value."\n";
            $propsHtml.=$name.': '.$valueHtml."<br/>";
            $result['PROP_'.$code.'_HTML']=$valueHtml;
            $result['PROP_'.$code]=$value;
            }   
      } 
      
      $result['USER_DESCRIPTION']=$order->getField('USER_DESCRIPTION');
      
      if(!empty($result['USER_DESCRIPTION']))
      {
        $props.='Описание'.': '.$result['USER_DESCRIPTION']."\n";
        $propsHtml.='Описание'.': '.$result['USER_DESCRIPTION']."<br/>";
      } 

$C_FIELDS=$result;
$C_FIELDS['ORDER_DATE']=date('d.m.Y');
$C_FIELDS['ORDER_ID']=$orderId;
$C_FIELDS['PROPS_LIST']=$props;
$C_FIELDS['PROPS_LIST_HTML']=$propsHtml;
$C_FIELDS['ORDER_LIST']=$textBasket;
$C_FIELDS['ORDER_LIST_HTML']=$textBasketHTML;
$C_FIELDS['PRICE']=$order->getPrice();
$C_FIELDS['WEIGHT']=round($weight/1000);

$paymentCollection = $order->getPaymentCollection();
$C_FIELDS['PAYMENT_PAID_SUM']=$paymentCollection->getPaidSum();
$payment = $paymentCollection[0];
$C_FIELDS['PAYMENT_SUM'] = $payment->getSum();
$C_FIELDS['PAYMENT_NAME'] = $payment->getPaymentSystemName();

$shipmentCollection = $order->getShipmentCollection();
$C_FIELDS['DELIVERY_ALL_SUM']=$shipmentCollection->getPriceDelivery();
$shipment=$shipmentCollection[0];
$shipment_nameDirty=$shipment->getDeliveryName();
$pieces = explode('(', $shipment_nameDirty);
$C_FIELDS['DELIVERY_NAME'] = $pieces[0];

$arFields=array_merge($arFields,$C_FIELDS);
}