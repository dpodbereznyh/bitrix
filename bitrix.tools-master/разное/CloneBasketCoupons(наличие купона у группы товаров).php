<?
CModule::IncludeModule("catalog");
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");

class CloneBasketCoupons{

	public $basketClone;

	public function init(){
		$registry = Bitrix\Sale\Registry::getInstance(Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
		$basketClass = $registry->getBasketClassName();
		
		$basket = $basketClass::loadItemsForFUser(
			Bitrix\Sale\Fuser::getId(),
			Bitrix\Main\Context::getCurrent()->getSite()
		);

		$this->basketClone = $basket->createClone();
		
	}
/*
	public function have_coupon(array $products_id)
	{
		$cache = Bitrix\Main\Data\Cache::createInstance();
		$taggedCache = Bitrix\Main\Application::getInstance()->getTaggedCache();
		$cacheId = md5(serialize($products_id));
		$cacheDir = "/have_coupon";
		$cacheTime = 3600 * 24;

		if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {

    // отдаём данные из кеша
			$result = $cache->getVars();

		} elseif ($cache->startDataCache()) {

			$array_elems=[1097559,1097561];
			$array_sections=[55078,57848,56072,24032,58130,24021];
			$result=[];
			foreach($products_id as $id)
				{   if(in_array($id,$array_elems))
					{$result[$id]=['HAVE'=>false];continue;}
					$db_groups = CIBlockElement::GetElementGroups($id, true);

					while($ar_group = $db_groups->Fetch()){
						{
							$list = CIBlockSection::GetNavChain(false,$ar_group["ID"], array(), true);
							foreach($list as $sect){
								if(in_array($sect['ID'],$array_sections))
									{$result[$id]=['HAVE'=>false];break;}}
							}
							if(empty($result[$id])){$result[$id]=['HAVE'=>true];}}
						}

						$taggedCache->EndTagCache();
						$cache->endDataCache($result);
					}		
					
					return $result;

	}*/

	public function have_coupon($cupon,array $products_id)
	{	
		$result=[];
		
		\Bitrix\Sale\DiscountCouponsManager::add($cupon);
		foreach($products_id as $id){
			$this->init();
			$basketItems = $this->basketClone->getBasketItems();
			foreach($basketItems as &$item1){
				$item1->delete();
			}
			$this->basketClone->refresh();
			//$this->basketClone->save();
			$item = $this->basketClone->createItem('catalog', $id); 
			$item->setFields(array(
				'QUANTITY' => 1,
				'CURRENCY' => 'RUB',
				'LID' => SITE_ID,
				'PRODUCT_PROVIDER_CLASS' =>\Bitrix\Catalog\Product\Basket::getDefaultProviderName()
			));
			$orderableBasket = $this->basketClone->getOrderableItems();
			unset($this->basketClone);
			
			if (!$orderableBasket->isEmpty()) {
				
				$discounts = \Bitrix\Sale\Discount::buildFromBasket(
					$orderableBasket,
					new \Bitrix\Sale\Discount\Context\Fuser(\Bitrix\Sale\Fuser::getId())
				);
				$discountResult = $discounts->calculate();
				$result_temp = $discounts->getApplyResult(true);
				$flgFind=false;
				foreach($result_temp['RESULT']['BASKET'] as $bask){
					foreach($bask as $discounts){
					if($discounts['COUPON_ID']==$cupon){$flgFind=true;break;}}
				}
				$result[$id]['RESULT']=array_values($result_temp['RESULT']['BASKET']);
				if(!empty($result[$id]['RESULT']))
					{$result[$id]['RESULT']=$result[$id]['RESULT'][0];}
				$result[$id]['HAVE']=$flgFind;
			}
		}

		$coupons = \Bitrix\Sale\DiscountCouponsManager::clear();
		return $result;
	}
};

/*
$cup=new CloneBasketCoupons();
$res2=$cup->have_coupon('BIRTHDAY',['1083818','1086482','880572']);
*/