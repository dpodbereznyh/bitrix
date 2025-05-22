<?require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

function product_count($product_id,$count,$cityCode,$personType=1)
{
  CModule::IncludeModule("iblock");
  CModule::IncludeModule("sale");

  \Bitrix\Sale\Compatible\DiscountCompatibility::stopUsageCompatible();
  $result=\Bitrix\Sale\Location\LocationTable::getList(array(
    'filter' => array('CODE'=>$cityCode,'NAME.LANGUAGE_ID'=>'ru'),
    'select' => array('CODE','ID','LANG_'=>'NAME'),
  ));

  $loc=$result->fetch();

  $order = \Bitrix\Sale\Order::create(SITE_ID, \Bitrix\Sale\Fuser::getId());
  $order->setPersonTypeId($personType);

  $basket = \Bitrix\Sale\Basket::create(\Bitrix\Sale\Fuser::getId());

  $item = $basket->createItem("catalog", $product_id);
  $fields = [ 'PRODUCT_ID' => $product_id, 
  'QUANTITY'   => $count, 
  'LID'        => SITE_ID,
  'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
];
$item->setFields($fields);
$discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId(true)));
$discounts->calculate();
$order->setBasket($basket);

$obj=CSaleLocation::GetLocationZIP($loc['ID']);
$zip=$obj->fetch();
$zip=$zip["ZIP"];  
$shipmentCollection = $order->getShipmentCollection();
$propertyCollection = $order->getPropertyCollection();
$propertyLocation = $propertyCollection->getDeliveryLocation();
$propertyLocation->setField('VALUE',$cityCode);

$shipment = $shipmentCollection->createItem();
$shipmentItemCollection = $shipment->getShipmentItemCollection();

$shipmentItem = $shipmentItemCollection->createItem($item);
$shipmentItem->setQuantity($count);


$deliveryList = \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);

$resultDelivery = [];

$paymentCollection = $order->getPaymentCollection();
$payment = $paymentCollection->createItem();
$payment->setFields(array(
  'PAY_SYSTEM_ID' => 13,
  'PAY_SYSTEM_NAME' => '',
));

foreach ($deliveryList as $delivery) {

  $shipment->setFields(array(
    'DELIVERY_ID' => $delivery->getId(),
    'DELIVERY_NAME' => $delivery->getName(),                         
  ));
  $propertyCollection = $order->getPropertyCollection();
  $propertyZip = $propertyCollection->getItemByOrderPropertyCode("ZIP");

  if($propertyZip){
    $propertyZip->setField('VALUE',$zip);
  }

  $calculationResult = $delivery->calculate($shipment);

  $shipment->setBasePriceDelivery($calculationResult->getPrice());

  $arShowPrices = $order->getDiscount()->getShowPrices();

  $data = $calculationResult->getData();

  $calculationResult->setData($arShowPrices);

  $calcResult = $delivery->calculate();
  $price=$calculationResult->getData()["DELIVERY"]['PRICE'];
  $res_price=\Bitrix\Sale\PriceMaths::roundPrecision($price);
  $delivery_info_res=\Bitrix\Sale\Delivery\Services\Table::getList(array('filter' => array('ID'=>$delivery->getId())));

  $delivery_info=$delivery_info_res->fetch();

  $resultDelivery[] = [
    'ID' => $delivery->getId(),
    'NAME' => $delivery->getName(),
    'TEXT' => $delivery->getDescription(),
    'PRICE_FORMATTED' => SaleFormatCurrency($res_price, 'RUB'),
    'PRICE' => $res_price,
    'INFO'=> $delivery_info
  ];

}

return $resultDelivery;

}

if(isset($_REQUEST['count'])&&isset($_REQUEST['id'])&&isset($_REQUEST['region']))
{
  $resultDelivery=product_count($_REQUEST['id'],$_REQUEST['count'],$_REQUEST['region']);
  ?>
  <script>
    console.log(<?=json_encode($resultDelivery, JSON_UNESCAPED_UNICODE)?>);
  </script>
  <?
?><div class="cont-deliv">
 <?foreach($resultDelivery as $delivery):?>
 <? $price=($delivery['PRICE'])?$delivery['PRICE_FORMATTED']:'<span>бесплатно</span>';?>
 <div class="delivery_item">

  <img src="<?=CFile::GetPath($delivery['INFO']['LOGOTIP']);?>">
  <div class="delivery-text">
    <div class="delivery-title">  <svg class="modal-delivery__list-item-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" width="32" height="32" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256">
      <path fill="currentColor" d="M104 196a12.2 12.2 0 0 1-8.5-3.5l-56-56a12 12 0 0 1 17-17L104 167L207.5 63.5a12 12 0 0 1 17 17l-112 112a12.2 12.2 0 0 1-8.5 3.5Z"></path>
    </svg><?=$delivery['INFO']['DESCRIPTION']?></div>
    <div class="delivery-price"><?=$price?></div>
  </div>
</div>
<?endforeach?>
</div>

<?}

         