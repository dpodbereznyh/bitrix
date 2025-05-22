<?require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

$basket_id = 's1';
//add update
if ($_REQUEST['action'] == 'ADDBASKET' && !empty($_REQUEST['PRODUCT_ID'])) {
    if (CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("main")) {

        //$name = "Выбран размер";

        $PRODUCT_ID = $_REQUEST['PRODUCT_ID'];
        $res        = \Bitrix\Sale\Basket::getList(array('select' => array('ID', 'QUANTITY'),
            'filter'                                                  => array(
                'PRODUCT_ID' => $PRODUCT_ID,
                'FUSER_ID'   => \Bitrix\Sale\Fuser::getId(),
                'ORDER_ID'   => null,
                'LID'        => $basket_id,

            ),
        ))->fetch();
        $count_plus = (isset($_REQUEST['QUANTITY'])) ? $_REQUEST['QUANTITY'] : 1;

        $res_prod=CIBlockElement::GetList([],['ID'=>$PRODUCT_ID]);
        $temp=$res_prod->GetNextElement();
        $product=$temp->GetFields();

        if (empty($res['ID'])) {

            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $basket_id);

            $item = $basket->createItem('catalog', $PRODUCT_ID);
            $item->setFields(array(
                'QUANTITY'               => $count_plus,
                'CURRENCY'               => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID'                    => $basket_id,
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                'NAME'=>$product['NAME']
            ));
            $item->save();
            if ($arrSize) {
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

            $rs = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
                'filter'                                          => array(
                    'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
                    'ORDER_ID' => null,
                    'LID'      => $basket_id,
                ),
            ));

            $arr = [];
            while ($ob = $rs->fetch()) {
                $arr[] = $ob;
            }

        } elseif (isset($_REQUEST['QUANTITY'])) {

            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), $basket_id);
            $item   = $basket->getItemById($res['ID']);
            $item->setField('QUANTITY', $item->getQuantity() + $count_plus);
            $basket->save();

            $rs = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
                'filter'                                          => array(
                    'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
                    'ORDER_ID' => null,
                    'LID'      => $basket_id,
                ),
            ));

            $obBasket = true;
            $arr      = [];
            while ($ob = $rs->fetch()) {
                $arr[] = $ob;
            }

        } else {
            echo 'added';
            die;}

        echo json_encode(['res' => !empty($obBasket), 'cnt' => count($arr)]);
    }
}

//delete
if ($_REQUEST['action'] == 'RBASKET' && !empty($_REQUEST['PRODUCT_ID'])) {
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
    }
}
//count
if ($_REQUEST['action'] == 'CNTBASKET') {
    if (CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("main")) {
        $rs = \Bitrix\Sale\Basket::getList(array('select' => array('ID'),
            'filter'                                          => array(
                'FUSER_ID' => \Bitrix\Sale\Fuser::getId(),
                'ORDER_ID' => null,
                'LID'      => $basket_id,
                'DELAY'=>'N'
            ),
        ));

        $arr = [];
        while ($ob = $rs->fetch()) {
            $arr[] = $ob;
        }
        echo count($arr);
    }
}

