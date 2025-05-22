<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));
if(!empty($arResult)){
?>
<div class="ask-cont">
<div class="h2">Вопросы-ответы</div>
<div class="row"><?
foreach($arResult as $item)
{
 ?><div class="ask-item col-sm-12 col-md-6 col-lg-6 col-xl-6" id="<?=$item['AREA_ID']?>">
     <div class="ask-title"><?=$item['PROPERTIES']['ASK']['VALUE']?><span class="plus">+</span></div>
     <div class="ask-body close"><?=$item['PROPERTIES']['ANSWER']['~VALUE']['TEXT']?></div>
 </div><?   
}
?></div></div><?

}


