<?
define('STOP_STATISTICS', true);
require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

Bitrix\Main\Loader::includeModule('catalog');

use Bitrix\Catalog\SubscribeTable;
use Bitrix\Catalog\Product\SubscribeManager;

global $USER;
$userId = $USER->GetID();
$product_id=$_REQUEST["ITEM_ID"];
if(!empty($product_id)&&$_REQUEST["action"]='unsub'){
   
    $filter = array(
        '=SITE_ID' => SITE_ID,
        '=ITEM_ID' => $product_id,
        array(
            'LOGIC' => 'OR',
            array('=DATE_TO' => false),
            array('>DATE_TO' => date($DB->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
        )
    );
    $filter['USER_ID'] = $userId;

    $queryObject = SubscribeTable::getList(array('select' => array('ID'), 'filter' => $filter));

    $subscribeManager = new SubscribeManager;
    $listRealItemId = array();
    $listRealItemId=[];
    while ($subscribe = $queryObject->fetch()) {
        $listRealItemId[] = $subscribe['ID'];
    }
if(!empty($listRealItemId)){
    $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
    if(!$subscribeManager->deleteManySubscriptions($listRealItemId, $product_id))
    {
        $errorObject = current($subscribeManager->getErrors());
        if($errorObject) {
            $errors = $errorObject->getMessage();
      }
    }
}
 header('Content-Type: application/json; charset=UTF-8');
 echo json_encode($listRealItemId);
    die;
}

if(!empty($product_id)){
     $filter = array(
        '=SITE_ID' => SITE_ID,
        '=ITEM_ID' => $product_id,
        array(
            'LOGIC' => 'OR',
            array('=DATE_TO' => false),
            array('>DATE_TO' => date($DB->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
        )
    );
    $filter['USER_ID'] = $userId;

    $queryObject = SubscribeTable::getList(array('select' => array('ID'), 'filter' => $filter));

    $subscribeManager = new SubscribeManager;
    $listRealItemId = array();
    $listRealItemId=[];
    while ($subscribe = $queryObject->fetch()) {
        $listRealItemId[] = $subscribe['ID'];
    }

    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($listRealItemId);
    die;
}