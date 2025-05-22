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
					if(!empty($result_temp['DISCOUNT_LIST'])){
						$result[$id]['DISCOUNT']=current($result_temp['DISCOUNT_LIST'])['ACTIONS_DESCR_DATA']['BASKET'][0];
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

		public function have_coupon_result($products_id){

			$cache = Bitrix\Main\Data\Cache::createInstance();
			$taggedCache = Bitrix\Main\Application::getInstance()->getTaggedCache();
			$cacheId = 'detail_cupon_'.$products_id;
			$cacheDir = "/detail_cupon";
			$cacheTime = 3600 * 24;

			if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
				return $cache->getVars();
			}else{

				$taggedCache->StartTagCache($cacheDir);
				$taggedCache->RegisterTag('iblock_id_158');

				$coupons=[];
				$cup=new CloneBasketCoupons();
				$res = CIBlockElement::GetList(['sort'=>'desc'], ['IBLOCK_ID'=>158,'ACTIVE'=>'Y'], false, false, ['IBLOCK_ID','ID','PROPERTY_CUPON']);
				while($ob = $res->GetNext())
				{
					$coupon=trim($ob['PROPERTY_CUPON_VALUE']);
					if(!empty($coupon)){
						$result[$coupon]=$cup->have_coupon($coupon,[$products_id]);
					}
				}

				$message='';
				foreach($result as $coupon=>$coupon_res){
					if($coupon_res[$products_id]['HAVE']){
						if(empty($message)){$message='<span class="coupon-sale"><img src="/upload/coupon-sale.png"/><div>';}
						$message.='<div>Скидка '.$coupon_res[$products_id]['DISCOUNT']['VALUE'].'% по промокоду <span class="coupon-val">'.$coupon.'</span></div>';
					}
				}
				if(!empty($message)){
					$message.='</div></span>';
				}

				$taggedCache->EndTagCache();
				$cache->endDataCache($message);

				return $message;
			}
		}
	};?>
