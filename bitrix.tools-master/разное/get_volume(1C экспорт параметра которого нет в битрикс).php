<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?

function set_volume1(){
	CModule::IncludeModule("iblock");
//var_dump($_SERVER['DOCUMENT_ROOT'].'/upload/1c_catalog/import.xml');
	$stream = fopen($_SERVER['DOCUMENT_ROOT'].'/upload/1c_catalog/import.xml', 'r');
	$xmlParser = xml_parser_create();

	$GLOBALS['ind']=0;
	$GLOBALS['start']=false;
	$GLOBALS['teg']=[];
	$GLOBALS['idFlag']=false;
	$GLOBALS['volFlag']=false;
	$GLOBALS['indId']=0;
	$GLOBALS['volFlagName']=false;
	$GLOBALS['volFlagVol']=false;
	$GLOBALS['NAME']='';
	$GLOBALS['simple']=[];

	function characterData($parser, $data)
	{

		if($GLOBALS['start']&&$GLOBALS['idFlag']&&$GLOBALS['indId']==1)
		{
			$GLOBALS['teg'][$GLOBALS['ind']]['id']=trim($data);
		}

		if($GLOBALS['start']&&$GLOBALS['volFlag']&&$GLOBALS['volFlagName'])
		{
			$GLOBALS['NAME']=trim($data);
		}

		if($GLOBALS['start']&&$GLOBALS['volFlag']&&$GLOBALS['volFlagVol'])
		{
			if($GLOBALS['NAME']=='Объем')
				{$GLOBALS['teg'][$GLOBALS['ind']]['vol']=trim($data);
			$GLOBALS['NAME']='';}

		}

		
	}

	function startElement($parser, $name, $attribs)
	{
		if($name=='Товар'){++$GLOBALS['ind'];$GLOBALS['start']=true;$GLOBALS['indId']=0;}
		if($name=='Ид'){$GLOBALS['idFlag']=true;++$GLOBALS['indId'];}
		if($name=='ЗначениеРеквизита'){$GLOBALS['volFlag']=true;}
		if($name=='Наименование'){$GLOBALS['volFlagName']=true;}
		if($name=='Значение'){$GLOBALS['volFlagVol']=true;}
	}

	function endElement($parser, $name)
	{
		if($name=='Товар'){$GLOBALS['start']=false;}
		if($name=='Ид'){$GLOBALS['idFlag']=false;}
		if($name=='ЗначениеРеквизита'){$GLOBALS['volFlag']=false;}
		if($name=='Наименование'){$GLOBALS['volFlagName']=false;}
		if($name=='Значение'){$GLOBALS['volFlagVol']=false;}
		
		if(($GLOBALS['ind']%=1000)==0){
			foreach($GLOBALS['teg'] as $key=>$val){
				if(!empty($val['vol'])){$GLOBALS['simple'][]=$val;}
			}
			$GLOBALS['teg']=[];
		}
	}

	xml_set_element_handler($xmlParser, "startElement", "endElement"); 
	xml_set_character_data_handler($xmlParser, "characterData"); 

	while (($data = fread($stream, 16384))) {
    xml_parse($xmlParser, $data); // разобрать текущую часть
}

xml_parse($xmlParser, '', true); // завершить разбор
xml_parser_free($xmlParser);
fclose($stream);

foreach($GLOBALS['teg'] as $key=>$val)
{
	if(!empty($val['vol'])){$GLOBALS['simple'][]=$val;}
}


$ids=[];
$idsVol=[];
foreach($GLOBALS['simple'] as $val)
{
	$ids[]=$val['id'];
	$idsVol[$val['id']]=$val['vol'];
}	

$gRes=CIBlockElement::GetList([],['IBLOCK_ID'=>14,'XML_ID'=>$ids],false,false,['XML_ID','IBLOCK_ID','ID','PROPERTY_VOLUME']);		

$idEl=[];

while($ob=$gRes->Fetch())
{
	if($ob['PROPERTY_VOLUME_VALUE']!=$idsVol[$ob['XML_ID']])
	{
		CIBlockElement::SetPropertyValuesEx($ob['ID'],false,array('VOLUME' =>str_replace('.',',',$idsVol[$ob['XML_ID']])));
	}
	$idEl[$ob['ID']]=$idsVol[$ob['XML_ID']];

}
?>
<script>
console.log(<?=json_encode($idEl, JSON_UNESCAPED_UNICODE)?>);
</script>
<?
}

set_volume1();