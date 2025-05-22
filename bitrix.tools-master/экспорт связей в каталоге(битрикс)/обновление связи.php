<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

include "catalog-export-sect.php";
$arrId=[];

$elem_r=CIBlockElement::GetList([],['IBLOCK_ID'=>3],false,false,['IBLOCK_ID','ID','EXTERNAL_ID']);

//var_dump($exp);

while($ob=$elem_r->Fetch())
{
	//var_dump($ob);
	//$arrId[$ob['ID']]=$sect;
	$arrId[$ob['EXTERNAL_ID']]=$ob['ID'];
}

$sect_r=CIBlockSection::GetList([],['IBLOCK_ID'=>3],false,['IBLOCK_ID','ID','EXTERNAL_ID']);
$sect=[];
while($ob=$sect_r->Fetch())
{
$sect[$ob['EXTERNAL_ID']]=$ob['ID'];
}


//ob_start();
//          echo '<?php '."\n".'$exp=';
//var_export($arrId2);

 //         $output = ob_get_clean();
         // file_put_contents($_SERVER['DOCUMENT_ROOT'].'/catalog-export-sect.php', $output,FILE_APPEND | LOCK_EX);

$el = new CIBlockElement;
$obj=[];
foreach($arrId as $id_xml=>$id)
{
	if(!empty($exp[$id_xml]['IBLOCK_SECTION_ID'])&&!empty($exp[$id_xml]['SECTION_ID']))
	{
		$arrId=[];
		if(!empty($exp[$id_xml]['SECTION_ID'])){
			
			foreach($exp[$id_xml]['SECTION_ID'] as $sid)
			{
				$arrId[]=$sect[$sid];
			}
		}
		$obj['one'][]=['IBLOCK_SECTION_ID'=>$sect[$exp[$id_xml]['IBLOCK_SECTION_ID']],'IBLOCK_SECTION'=>$arrId];
		$rs=$el->Update($id,
			['IBLOCK_SECTION_ID'=>$sect[$exp[$id_xml]['IBLOCK_SECTION_ID']],
			'IBLOCK_SECTION'=>$arrId],
			false, true, true);
	}

	if(empty($exp[$id_xml]['IBLOCK_SECTION_ID'])&&!empty($exp[$id_xml]['SECTION_ID']))
	{
		$obj['two'][]=['IBLOCK_SECTION_ID'=>$exp[$id_xml]['SECTION_ID'][0],'IBLOCK_SECTION'=>$exp[$id_xml]['SECTION_ID']];
		//$rs=$el->Update($id,
		//	['IBLOCK_SECTION_ID'=>$exp[$id_xml]['SECTION_ID'][0],
		//	'IBLOCK_SECTION'=>$exp[$id_xml]['SECTION_ID']],
		//	false, true, true);
	}
	if(!empty($exp[$id_xml]['IBLOCK_SECTION_ID'])&&empty($exp[$id_xml]['SECTION_ID']))
	{
		$obj['three'][]=['IBLOCK_SECTION_ID'=>$exp[$id_xml]['IBLOCK_SECTION_ID']];
		//$rs=$el->Update($id,
		//	['IBLOCK_SECTION_ID'=>$exp[$id_xml]['IBLOCK_SECTION_ID']],
		//	false, true, true);
	}
}

?>

<script>
	console.log(<?=json_encode($sect, JSON_UNESCAPED_UNICODE)?>,'one');
console.log(<?=json_encode($obj, JSON_UNESCAPED_UNICODE)?>,'one');
//console.log(<?=json_encode($exp, JSON_UNESCAPED_UNICODE)?>);
</script>