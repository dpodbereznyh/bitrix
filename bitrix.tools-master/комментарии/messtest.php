<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?

$APPLICATION->IncludeComponent('forumedia:messages',
  '',
  array(
 'IBLOCK_ID'=>38,
 'ID'=>'624',
 'PROPERTY_CODES'=>array('316','317','318','319','320','321','322','323','NAME'),
 'PROPERTY_CODES_REQUIRED'=>array(''),
 'SELECT'=>array('TIMESTAMP_X','PROPERTY_FILES','PROPERTY_CATALOG','PROPERTY_POSITIVE','PROPERTY_NEGATIVE','PROPERTY_STARS','PROPERTY_MESSAGE','PROPERTY_NAME','PROPERTY_CITY','ID','NAME'),
 'SORT'=>empty($_REQUEST['commentSort'])?array('timestamp_x'=>'desc'):array('property_STARS'=>$_REQUEST['commentSort']),
 'PAGE'=>isset($_REQUEST['page'])?['iNumPage'=>$_REQUEST['page'],'nPageSize'=>$_REQUEST['psize']]:false
));

?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>