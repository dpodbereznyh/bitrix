<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php
use Bitrix\Main\Loader;

Loader::includeModule("iblock");
Loader::includeModule("main");
global $USER;
$USER_ID=false;
if($USER->IsAuthorized()){
 $USER_ID=$USER->GetID();
}else{
 $USER_ID=\Bitrix\Main\Service\GeoIp\Manager::getRealIp();
}

$res = CIBlockElement::GetList(
    array(),
    array('IBLOCK_ID' => $_REQUEST['IBLOCK_ID'],'ID'=>$_REQUEST['ID'],"PROPERTY_USERS"=>$USER_ID),
    false,
    false,
    array('ID')
);
$obj=null;
$users=[];
if(!$res->Fetch()) {
    $res2 = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID'           => $_REQUEST['IBLOCK_ID'],
              'ID'                  => $_REQUEST['ID']
        ),
        false,
        false,
        array('ID', 'PROPERTY_USERS', 'PROPERTY_POSITIVE', 'PROPERTY_NEGATIVE')
    );

    while($tmp=$res2->Fetch())
    {   $obj=$tmp;
        $users[]=$tmp['PROPERTY_USERS_VALUE'];
    }
}



$val='';

if(isset($obj)&&$_REQUEST['VALUE']=='plus')
{
    CIBlockElement::SetPropertyValuesEx($obj['ID'],$_REQUEST['IBLOCK_ID'],array('POSITIVE'=>$obj['PROPERTY_POSITIVE_VALUE']+1));
    $users[]=$USER_ID;
    CIBlockElement::SetPropertyValuesEx($obj['ID'],$_REQUEST['IBLOCK_ID'],array('USERS'=>$users));
    $val=$obj['PROPERTY_POSITIVE_VALUE']+1;
}
if(isset($obj)&&$_REQUEST['VALUE']=='minus')
{
    CIBlockElement::SetPropertyValuesEx($obj['ID'],$_REQUEST['IBLOCK_ID'],array('NEGATIVE'=>$obj['PROPERTY_NEGATIVE_VALUE']+1));
    $users[]=$USER_ID;
    CIBlockElement::SetPropertyValuesEx($obj['ID'],$_REQUEST['IBLOCK_ID'],array('USERS'=>$users));
    $val=$obj['PROPERTY_NEGATIVE_VALUE']+1;
}

echo $val;
?>
