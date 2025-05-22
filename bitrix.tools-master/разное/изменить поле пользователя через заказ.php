<?
\Bitrix\Main\EventManager::getInstance()->addEventHandler( 
	'sale', 
	'onSaleOrderSaved', 
	'onSaleOrderSaved'
);

function onSaleOrderSaved(\Bitrix\Main\Event $event){
	$order = $event->getParameter("ENTITY"); 
	$isNew = $event->getParameter("IS_NEW");
	if(empty($isNew)){return;}

	$propertyCollection = $order->getPropertyCollection();
	$somePropValue = $propertyCollection->getItemByOrderPropertyId(25);
	if($somePropValue){
		$PERSONAL_BIRTHDAY=$somePropValue->getValue();
		if(!empty($PERSONAL_BIRTHDAY)){
			$id_user=$order->getUserId();
			$user = new CUser;
			$fields = array("PERSONAL_BIRTHDAY" => $PERSONAL_BIRTHDAY);
			$user->Update($id_user, $fields);}

		}
	}

?>