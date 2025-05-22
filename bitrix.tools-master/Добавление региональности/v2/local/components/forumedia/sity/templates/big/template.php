<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
if(empty($arParams['SITY'])):?>
<div class="regions">
<div class="regions-left">

<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" class="region-ico">
                <g>
                    <g>
                        <path d="M256,0C153.755,0,70.573,83.182,70.573,185.426c0,126.888,165.939,313.167,173.004,321.035
                            c6.636,7.391,18.222,7.378,24.846,0c7.065-7.868,173.004-194.147,173.004-321.035C441.425,83.182,358.244,0,256,0z M256,278.719
                            c-51.442,0-93.292-41.851-93.292-93.293S204.559,92.134,256,92.134s93.291,41.851,93.291,93.293S307.441,278.719,256,278.719z"></path>
                    </g>
                </g>
            </svg>


</div>
<div class="regions-center" title="<?=$GLOBALS['REGIONS']['ACTIVE'][0]['NAME'];?>">
<?=$GLOBALS['REGIONS']['ACTIVE'][0]['NAME'];?>
   </div>
    <div class="region-right">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="9px" height="5px" x="0px" y="0px" class="region-arrow">
                <defs>
                    <filter id="Filter_0">
                        <feFlood flood-color="rgb(110, 141, 156)" flood-opacity="1" result="floodOut"></feFlood>
                        <feComposite operator="atop" in="floodOut" in2="SourceGraphic" result="compOut"></feComposite>
                        <feBlend mode="normal" in="compOut" in2="SourceGraphic"></feBlend>
                    </filter>
                </defs>
                <g filter="url(#Filter_0)">
                    <path fill-rule="evenodd" fill="rgb(95, 95, 95)" d="M8.996,0.835 L4.836,4.994 L4.500,4.658 L4.164,4.994 L0.004,0.835 L0.836,0.003 L4.500,3.667 L8.164,0.003 L8.996,0.835 Z"></path>
                </g>
            </svg>
</div>

</div>
<?endif?>
<?if(!empty($arParams['SITY'])):?>

<?if(empty($arParams['FORM'])): ?>
<? $APPLICATION->RestartBuffer(); ?>
<? $stradd='';
/*if(strpos($arParams['PAGE'],'region=Y')===false)
{
    $stradd=(strpos($arParams['PAGE'],'?')===false)?('?region=Y'):('&region=Y');
}*/
?>
    <?foreach($arResult['OPTION'] as $item):?>
    <div class="regions-body-items">
        <a href="https://<?=$item['PROPERTY_DOMEN_VALUE']?><?=$arParams['PAGE'].$stradd;?>"><?=$item['NAME']?></a></div>
    <?endforeach?>
<?if(empty($arResult['OPTION'])):?>
<?foreach($arResult['VIEW'] as $item):?>
        <div class="regions-body-items">
            <a href="https://<?=$item['PROPERTY_DOMEN_VALUE']?><?=$arParams['PAGE'].$stradd;?>"><?=$item['NAME']?></a></div>
<?endforeach?>
<?endif?>
<? die(); ?>
<?endif?>

<div class="background-fill">

<div class="regions-form">
    <div class="regions-close">
    <svg xmlns="http://www.w3.org/2000/svg" version="1" viewBox="0 0 24 24"><path d="M13 12l5-5-1-1-5 5-5-5-1 1 5 5-5 5 1 1 5-5 5 5 1-1z"></path></svg>
    </div>

    <div class="regions-input">
    <input type="text" autocomplete="off" placeholder="Введите название города" id="regionSearch">
    </div>
<? $stradd='';
/*if(strpos($arParams['PAGE'],'region=Y')===false)
{
    $stradd=(strpos($arParams['PAGE'],'?')===false)?('?region=Y'):('&region=Y');
}*/
?>
    <div class="regions-body">
    <?foreach($arResult['VIEW'] as $item):?>
        <div class="regions-body-items">
 <a href="https://<?=$item['PROPERTY_DOMEN_VALUE']?><?=$arParams['PAGE'].$stradd?>"><?=$item['NAME']?></a></div>
    <?endforeach?>
    </div>
</div>
<div>
<?endif?>