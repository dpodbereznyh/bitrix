<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?
$APPLICATION->IncludeComponent('forumedia:messages',
  '',
  array(
 'IBLOCK_ID'=>$_REQUEST['IBLOCK_ID'],
 'ID'=>$_REQUEST['ID'],
 'PROPERTY_CODES'=>array('144','145','146','147','148','149','150','151','NAME'),
 'PROPERTY_CODES_REQUIRED'=>array(''),
 'SELECT'=>array('TIMESTAMP_X','PROPERTY_FILES','PROPERTY_CATALOG','PROPERTY_POSITIVE','PROPERTY_NEGATIVE','PROPERTY_STARS','PROPERTY_MESSAGE','PROPERTY_NAME','PROPERTY_CITY','ID','NAME'),
 'SORT'=>empty($_REQUEST['commentSort'])?array('timestamp_x'=>'desc'):array('property_STARS'=>$_REQUEST['commentSort']),
 'PAGE'=>isset($_REQUEST['page'])?['iNumPage'=>$_REQUEST['page'],'nPageSize'=>$_REQUEST['psize']]:false
));

?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>