<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
use Bitrix\Main\Loader;

include "addimg.php";

$IBLOCK_ID=24;
$PROPERTY_VALUE=105;
//37-228-101, 38-229-102, 30-230-103, 26-231-104, 24-232-105

Loader::includeModule("iblock");
Loader::includeModule("main");
Loader::includeModule("highloadblock");
Loader::includeModule("catalog");
global $USER;
if($USER->IsAdmin()){}else{die;}
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
if(empty($_REQUEST['PRODUCT_ID']))
{
	$res = CIBlockElement::GetList(
		array(),
		array('IBLOCK_ID' => array($IBLOCK_ID),'ACTIVE'=>'Y'),
		false,
		false,
		array('ID','CODE','NAME')
	);

    echo "<h2>Элемент из списка</h2>";
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <form id="product-id" action="/toOffersById.php" method="post" >
    <select name="PRODUCT_ID[]" multiple="multiple" size="30">
        <?
	while($arEl = $res->GetNext())
    {
    ?><option value="<?=$arEl['ID']?>"><?=$arEl['NAME']?> (<?=$arEl['ID']?>)</option><?
	}
    ?>
    </select>
    </form>
<br/><br/>
<input type="button" class="submit-all" value="добавить водяной знак"/>
<br/><br/>
<div class="input">Элементы:<span></span></div>
<div class="result"></div>
<script>
function recursProd(i,arr)
{
	$.post("/scripts/watermark.php",[{name:'all',value:'Y'},{name:'PRODUCT_ID',value: arr[i].value}])
		.done(function(result){$(".result").append("<div> "+(i+1)+". ID: "+arr[i].value+" - "+result+"</div>");
							   if(i+1<arr.length){recursProd(i+1,arr);}
})
}

	let ajaxProd=function(){
$('.submit-all').css('opacity',0);
let arr=$("#product-id").serializeArray();
console.log(arr)
let str='';
for(let i=0;i<arr.length;i++)
{
str+=(arr[i].value+", ");
}
console.log(str);
$(".input span").html(str);

recursProd(0,arr);
$('.submit-all').css('opacity',1);
}

		$(".submit-all").on('click',ajaxProd)
</script>

<?php
    die();
}

$res = CIBlockElement::GetList(
   array(),
   array('IBLOCK_ID' => $IBLOCK_ID,'ID'=>$_REQUEST['PRODUCT_ID']),
   false,
   false,
	//['ID','NAME','DETAIL_PICTURE','PREVIEW_PICTURE','PROPERTY_PHOTOS','PROPERTY_GALLEY_BIG']
	['ID','PROPERTY_ADD_WATERMARK']
);

global $imageWater;

$numbers=[];
$img = new WImgOverlayInit();

/*
CModule::IncludeModule("fileman");
CMedialib::Init();
$arRes = CMedialibItem::GetList(array('arCollections' => array()));
foreach($arRes as $item)
{
$path=$_SERVER['DOCUMENT_ROOT'].$item['PATH'];
$img->image_overlay($path, $imageWater, $path);
}
*/

/*
while($arEl = $res->Fetch()){
$ELEMENT_ID=$arEl['ID'];
$PROPERTY_CODE='ADD_WATERMARK'; 
CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, $IBLOCK_ID,array($PROPERTY_CODE=>$PROPERTY_VALUE));
	if(!isset($numbers[$arEl['PREVIEW_PICTURE']]))
{
$numbers[$arEl['PREVIEW_PICTURE']]='Y';
		$path=CFile::GetPath($arEl['PREVIEW_PICTURE']);
		$path=$_SERVER['DOCUMENT_ROOT'].$path;
$img->image_overlay($path, $imageWater, $path);
	}

if(!isset($numbers[$arEl['DETAIL_PICTURE']]))
{
$numbers[$arEl['DETAIL_PICTURE']]='Y';
$path=CFile::GetPath($arEl['DETAIL_PICTURE']);
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
$img->image_overlay($path, $imageWater, $path);
	}

if(isset($arEl['PROPERTY_PHOTOS_VALUE']))
{
$path=CFile::GetPath($arEl['PROPERTY_PHOTOS_VALUE']);
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
$img->image_overlay($path, $imageWater, $path);
}

if(isset($arEl['PROPERTY_GALLEY_BIG_VALUE']))
{
$path=CFile::GetPath($arEl['PROPERTY_GALLEY_BIG_VALUE']);
	$path=$_SERVER['DOCUMENT_ROOT'].$path;
$img->image_overlay($path, $imageWater, $path);
}
}
*/




