<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("main");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("iblock");
if($_POST["order-item"]){
	$IBLOCK_ID = 11; 
	$ID = $_POST["order-item"]; 
	$arInfo = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
	if (is_array($arInfo)) 
	{ 
		$rsOffers = CIBlockElement::GetList(array(),array('IBLOCK_ID' => $arInfo['IBLOCK_ID'], 'PROPERTY_'.$arInfo['SKU_PROPERTY_ID'] => $ID, 'PROPERTY_FORMAT_OFFER_VALUE'=>$_POST["flyer_format"])); 
		while ($arOffer = $rsOffers->GetNext()) 
		{
			$arResult["OFFER"] = $arOffer;
		} 
	}
	$f = array();
	if($_POST["postprocessing"] == "on")
	{
		$f[] = array("NAME" => "Послепечатная обработка", "CODE" => "PPO", "VALUE" => "Да");
	}
	if(!empty($_POST["full_color_printing"] ))
	{
		$f[] = array("NAME" => "Полноцветная печать", "CODE" => "PP_OFFER", "VALUE" => $_POST["full_color_printing"]);
	}
	if(!empty($_POST["flyer_format"]))
	{
		$f[] = array("NAME" => "Формат", "CODE" => "FORMAT_OFFER", "VALUE" => $_POST["flyer_format"]);
	}
	if(!empty($_POST["paper_density"]))
	{
		$f[] = array("NAME" => "Плотность бумаги", "CODE" => "PB_OFFER", "VALUE" => $_POST["paper_density"]);
	}
	if(!empty($_POST["type_buklet"]))
	{
		$f[] = array("NAME" => "Тип буклета", "CODE" => "TYPE_BUKLET", "VALUE" => $_POST["type_buklet"]);
	}
    if(!empty($_POST["maket_id"]))
    {
        $el=CIBlockElement::GetList([],['IBLOCK_ID'=>16,'ID'=>$_POST["maket_id"]],false,false,['IBLOCK_ID','ID','PROPERTY_FILES']);
        $i=0;
        while($elem=$el->Fetch())
        {
            if(!empty($elem['PROPERTY_FILES_VALUE']))
            {++$i;
            $path=CFile::GetPath($elem['PROPERTY_FILES_VALUE']);
            $f[] = array("NAME" => "Макет", "CODE" => "WMAKET_PATH_".$i, "VALUE" => 'http://'.$_SERVER['SERVER_NAME'].$path);
            }
        }
    }
	
    $res = Add2BasketByProductID($arResult["OFFER"]["ID"], intval($_POST["circulation"]), $f);
    if($res)
    {
        if(!empty($_POST["maket_id"]))
        {
            global $USER;
            $USER_ID=false;
            if($USER->IsAuthorized()){
                $USER_ID=$USER->GetID();
            }else{
                $USER_ID=\Bitrix\Main\Service\GeoIp\Manager::getRealIp();
            }
            CIBlockElement::SetPropertyValueCode($_POST["maket_id"], "BASKET_ID", $res);
            CIBlockElement::SetPropertyValueCode($_POST["maket_id"], "USER_ID", $USER_ID);
        }
    	
    }
}