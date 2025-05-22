<?
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");

function OnBeforeUserUpdateHandler(&$arFields)
{  
    $arGroups=[];
    if(!empty($arFields["ID"])){    
        $arGroups1 = \Bitrix\Main\UserGroupTable::getList([
            'filter' => ['USER_ID' => $arFields["ID"]]
        ])->fetchAll();
        foreach($arGroups1 as $arGroup){
            $arGroups[]=$arGroup['GROUP_ID'];
        }
    }

//удаляем группу
    if($arFields["UF_GAMER"] != 49){
        if(empty($arFields["GROUP_ID"])&&!empty($arFields['ID'])&&in_array('12',$arGroups))
        {
            \Bitrix\Main\UserGroupTable::delete([
                "USER_ID" => $arFields['ID'],
                "GROUP_ID" => 12,
            ]);
        }
        if(!empty($arFields["GROUP_ID"])&&!empty($arFields['ID'])&&in_array('12',$arGroups)){
            foreach($arFields["GROUP_ID"] as $key=>$val){
                if($val["GROUP_ID"]==12)
                    {unset($arFields["GROUP_ID"][$key]);break;}
            }
        }

    }
//добавляем группу
    if($arFields["UF_GAMER"] == 49) {

        if(empty($arFields["GROUP_ID"])&&!empty($arFields['ID'])&&!in_array('12',$arGroups)){
            \Bitrix\Main\UserGroupTable::add([
                "USER_ID" => $arFields['ID'],
                "GROUP_ID" => 12,
            ]);
        }

        if(!empty($arFields["GROUP_ID"])&&!empty($arFields['ID'])&&!in_array('12',$arGroups)){
           $arFields["GROUP_ID"][] = 12;
       }

   }

    }


//после сохранения заказа обновляем свойство пользователя. Группа добавится автоматом
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
         $somePropValue = $propertyCollection->getItemByOrderPropertyId(103);
         if($somePropValue){
         $gamer=$somePropValue->getValue();
         $id_user=$order->getUserId();
         if($gamer=='Y'){
             $user = new CUser;
             $fields = array("UF_GAMER" => 49);
             $user->Update($id_user, $fields);
         }

         if($gamer=='N'){
             $user = new CUser;
             $fields = array("UF_GAMER" => 50);
             $user->Update($id_user, $fields);
         }

     }
        

}
//----------------------------------------------------------------------------------------------------------------    
//при регистрации добавляем группу пользователю
   AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler"); 
   function OnAfterUserAddHandler(&$arFields) 
   { 
     if($arFields["ID"] > 0) 
     { 
         $arGroups = CUser::GetUserGroup($arFields["ID"]); 
         $arGroups[] = 8; 
         CUser::SetUserGroup($arFields["ID"], $arGroups); 
     } 
   }  

//после первой покупки убираем группу из списка групп пользователя
   function ModifyOrderSaleMails($orderID, &$eventName, &$arFields)
{
    if (Cmodule::IncludeModule("sale")) {


        $order = Sale\Order::load($orderID);
        $userId=$order->getUserId();
        if(!empty($userId))$groups=CUser::GetUserGroup($userId);
        if($groups&&in_array('8',$groups))
        {
            $groups=array_diff($groups, ['8']); 
            CUser::SetUserGroup($userId, $groups);
        }}
}  