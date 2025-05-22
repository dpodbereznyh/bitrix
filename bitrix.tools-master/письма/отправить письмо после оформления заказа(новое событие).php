<?
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderEntitySaved',
    'onSaleOrderEntitySaved'

);

function onSaleOrderEntitySaved(\Bitrix\Main\Event $event)
{
    CModule::IncludeModule("iblock");
    CModule::IncludeModule("catalog");
    CModule::IncludeModule("sale");
    CModule::IncludeModule("main");
    $order = $event->getParameter("ENTITY");
    $isNew = $event->getParameter("IS_NEW");
    if(empty($isNew)){return;}
    $shipmentCollection = $order->getShipmentCollection();
    $shipment=$shipmentCollection[0];
    $shipment_nameDirty=$shipment->getDeliveryName();
    $pieces = explode('(', $shipment_nameDirty);
    $delivery_name = $pieces[0];
    $basketOrder=$order->getBasket();
    $basketItems = $basketOrder->getBasketItems();
    if(!$order->getId())return;
    $orderId=$order->getId(); 
    $date_insert=$order->getDateInsert();
    $date_insert_timestamp=$date_insert->getTimestamp();

    if(time()-$date_insert_timestamp>60)return;
    
    $textBasket="";
    $textBasketHTML="";
    $weight=0;
    $textBasketLines="";
    $volume=0;
    foreach ($basketItems as $key => $item)
    {
        $pid=$item->getProductId();
        $price=$item->getPrice();
        $quantity=$item->getQuantity();
        $weight+=$item->getWeight()*$quantity;
        $name=$item->getField('NAME');
        $textBasket.=$name.' - '.$price.' руб. x '.$quantity." шт. \n";
        $textBasketHTML.=$name.' - '.$price.' руб. x '.$quantity." шт.<br/>";
        $res = CIBlockElement::GetList([],['ID'=>$pid]);
        $ob=$res->GetNextElement();
        $fields=$ob->GetFields();
        $properties=$ob->GetProperties();
    //$catalog = \Bitrix\Catalog\ProductTable::getList(array('filter' => array('=ID'=>$pid)));
        $volume_add=(!empty($properties['VOLUME']['VALUE']))?(floatval(str_replace(',', '.', $properties['VOLUME']['VALUE'])).' м&sup3;'):'';
        $volume+=(!empty($properties['VOLUME']['VALUE']))?(floatval(str_replace(',', '.', $properties['VOLUME']['VALUE']))*$quantity):0;
        $weight_pos=(!empty($item->getWeight()))?((round($item->getWeight()/1000)).' кг'):('');
        $textBasketLines.='<tr><td>'.$properties['CML2_ARTICLE']['VALUE'].'</td><td>'.$fields['NAME'].'</td><td style="white-space: nowrap;">'.$quantity.' шт</td><td style="white-space: nowrap;">'.$weight_pos.'</td><td>'.$volume_add.'</td><td>'.$price.' руб</td></tr>';
    }

    $result=[];
    $collection = $order->getPropertyCollection();
    $props='';
    $propsTable='';
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
            $propsTable.='<tr><td>'.$name.': </td><td style="min-width:200px"> '.$valueHtml.' </td></tr>';
        }
    }
      //$propsTable.='<tr><td>Доставка</td><td style="min-width:200px">'.$delivery_name.'</td></tr>';


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
    $C_FIELDS['PROPS_LIST_TABLE']=$propsTable;
    $C_FIELDS['ORDER_LIST']=$textBasket;
    $C_FIELDS['ORDER_LIST_HTML']=$textBasketHTML;
    $C_FIELDS['ORDER_LIST_TABLE']=$textBasketLines;
    $C_FIELDS['PRICE']=$order->getPrice();
    $C_FIELDS['WEIGHT']=(!empty($weight))?(round($weight/1000).' кг'):'';
    $C_FIELDS['VOLUME']=(!empty($volume))?($volume.' м&sup3;'):'';


    Bitrix\Main\Mail\Event::send(array(
      "EVENT_NAME" => "SALE_NEW_ORDER_1",
      "LID" => "s1",
      "C_FIELDS" => $C_FIELDS,
  ));

}
