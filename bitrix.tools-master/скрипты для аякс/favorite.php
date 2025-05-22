<?require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

$basket_id='f1';
//add
if ($_REQUEST['action'] == 'ADDFAVORITE' && !empty($_REQUEST['PRODUCT_ID'])) {
    if (CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("main")) {

        //$name = "Выбран размер";

        $PRODUCT_ID = $_REQUEST['PRODUCT_ID'];
        $res        = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
            'filter'                                                  => array(
                'PRODUCT_ID' => $PRODUCT_ID,
                'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                'ORDER_ID'   => null,
                'LID'        => $basket_id,

            ),
        ))->fetch();

        if (empty($res['ID'])) {

            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $basket_id);
           
                $item = $basket->createItem('catalog', $PRODUCT_ID);
                $item->setFields(array(
                    'QUANTITY'               => 1,
                    'CURRENCY'               => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                    'LID'                    => $basket_id,
                    'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                ));
                $item->save();
                if($arrSize){
                $basketPropertyCollection = $item->getPropertyCollection();
                $basketPropertyCollection->setProperty($arrSize);
                $basketPropertyCollection->save();
                }

            
            $basket->save();

            $obBasket = \Bitrix\Sale\Basket::getList(array('select' => array('ID', 'NAME'),
                'filter'                                                => array(
                    'PRODUCT_ID' => $PRODUCT_ID,
                    'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                    'ORDER_ID'   => null,
                    'LID'        => $basket_id,
                ),
            ))->fetch();

        $rs= \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
            'filter'=> array(
                'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                'ORDER_ID'   => null,
                'LID'        => $basket_id
            ),
        ));
    
        $arr=[];
        while($ob=$rs->fetch())
        {
        $arr[]=$ob;
        }
         //делаем пометку
        if(!empty($obBasket))
        {
            $val_res=CIBlockElement::GetList([],['ID'=>$PRODUCT_ID],false,false,['IBLOCK_ID','ID','PROPERTY_COUNT_FAVORITE']);
            $val=$val_res->Fetch();
            CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, 11, ['COUNT_FAVORITE'=>($val['PROPERTY_COUNT_FAVORITE_VALUE']+1)]);
        }

        } else {
            echo 'added';
            die;
        }

       echo json_encode(['res'=>!empty($obBasket),'cnt'=>count($arr)]);

    }
}
//delete
if ($_REQUEST['action'] == 'RFAVORITE' && !empty($_REQUEST['PRODUCT_ID'])) {
if (CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("main")) {
    $PRODUCT_ID = $_REQUEST['PRODUCT_ID'];
        $res        = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
            'filter'                                                  => array(
                'PRODUCT_ID' => $PRODUCT_ID,
                'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                'ORDER_ID'   => null,
                'LID'        => $basket_id,

            ),
        ))->fetch();

        if (!empty($res['ID'])) {

            //$arrSize = (empty($_REQUEST['size'])) ? false : [["NAME" => $name, "CODE" => 'CSIZE', "VALUE" => $_REQUEST['size'],'SORT'=>100]];

            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $basket_id);
           
            $basket->getItemById($res['ID'])->delete();
            $basket->save();

            $obBasket = \Bitrix\Sale\Basket::getList(array('select' => array('ID', 'NAME'),
                'filter'                                                => array(
                    'PRODUCT_ID' => $PRODUCT_ID,
                    'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                    'ORDER_ID'   => null,
                    'LID'        => $basket_id,
                ),
            ))->fetch();} else {
            echo 'added';
            die;
        }

        echo empty($obBasket);
}}

if ($_REQUEST['action'] == 'CNTFAVORITE' ) 
{
    if (CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("main")) 
    {
       $rs= \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
            'filter'=> array(
                'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                'ORDER_ID'   => null,
                'LID'        => $basket_id
            ),
        ));
    
    $arr=[];
    while($ob=$rs->fetch())
    {
        $arr[]=$ob;
    }
    echo count($arr);}
}
