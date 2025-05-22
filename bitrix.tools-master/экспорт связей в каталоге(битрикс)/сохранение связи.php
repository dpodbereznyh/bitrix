<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$arrId=[];

$elem_r=CIBlockElement::GetList([],['IBLOCK_ID'=>3],false,false,['IBLOCK_ID','ID','IBLOCK_SECTION_ID','IBLOCK_SECTION','EXTERNAL_ID']);

while($ob=$elem_r->Fetch())
{
	
	//var_dump($ob);
	$sect=[];
	$sect['IBLOCK_SECTION_ID']=$ob['IBLOCK_SECTION_ID'];
	$db_groups = CIBlockElement::GetElementGroups($ob['ID']);
	$groups=[];
	while($ob2 = $db_groups->Fetch())
	{
		$groups[]=$ob2['ID'];
	}
	$sect['SECTION_ID']=$groups;
	$sect['EXTERNAL_ID']=$ob['EXTERNAL_ID'];

	//$arrId[$ob['ID']]=$sect;
	$arrId2[$ob['EXTERNAL_ID']]=$sect;
}



ob_start();
          echo '<?php '."\n".'$exp=';
var_export($arrId2);

          $output = ob_get_clean();
          file_put_contents($_SERVER['DOCUMENT_ROOT'].'/catalog-export-sect.php', $output,FILE_APPEND | LOCK_EX);

//$el = new CIBlockElement;
//foreach($arrId as $id_el=>$sect)
//{

	//$rs=$el->Update($id_el,['IBLOCK_SECTION_ID'=>$sect['IBLOCK_SECTION_ID'],'IBLOCK_SECTION'=>$sect['SECTION_ID']],false, true, true);
//}


?>