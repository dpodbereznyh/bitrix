<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


                    ?><script>
console.log(<?=json_encode($arResult, JSON_UNESCAPED_UNICODE)?>,'basket');
</script><? 

?>
<div class="wrapper_inner  wide_page cont-basket">
    <div class="middle">
        <div class="container">
            <div class="basket">
                <?if(!empty($arResult['ITEMS'])):?>
    <div class="table">
<div class="table-row pc-label">
        <div class="th-articul">
            Артикул
        </div>
        <div class="th-name">
            Наименование
        </div>
        <div class="th-count">
            Количество
        </div>
        <div class="th-price_1">
            Цена 
        </div>
        <div class="th-count_price">
            Итого
        </div></div>
        <?foreach($arResult['ITEMS'] as $key=>$items):?>
        <div class="table-row" data-id="<?=$items['ID']?>">
        <div class="td-articul">
             <div class="mobile-label">Артикул: </div>
            <?=$items['PRODUCT']['PROPERTIES']['CML2_ARTICLE']['VALUE']?>
        </div>
        <div class="td-name">
            <span class="td-name__img"><img src="<?=$items['PREVIEW_PICTURE']['SRC']?>" alt="<?=$items['PRODUCT']['FIELDS']['NAME']?>"></span>
            <span class="name-val"><div class="mobile-label">Название: </div>
            
            <?=$items['PRODUCT']['FIELDS']['NAME']?></span>
        </div>
        <div class="td-count ">
            <div class="mobile-label">Количество: </div>
            <div class="basket-item-block-amount">
                                                    <a class="down">-</a>
                                                    <input class="cnt" id="quantity_id_<?=$items['ID']?>" name="quantity_name_<?=$items['ID']?>" size="2" value="<?=$items['QUANTITY']?>" type="text"/>
                                                    <a class="up">+</a>
                                                    </div>
                                                    
        </div>
        <div class="td-price_1">
            <div class="mobile-label">Цена: </div>
            <?=$items['PRICES']['PRINT_PRICE']?>
        </div>
        <div class="td-count_price">
            <div class="mobile-label">Итого: </div>
            <span class="item_price_total"><?=$items['PRICES']['PRINT_FINAL_PRICE']?></span>
            <a class="btnNormal btn-del"><span class="icoClose">x</span></a>
        </div>
            
    </div>
        <?endforeach?>
    </div>
           

            <div class="result-val">
               <div class="result-val__title">Ваш заказ</div>
               <div class="result-val__count"><span class="title">Количество товаров:</span><span class="value"><?=count($arResult['ITEMS'])?></span></div>
                <div class="result-val__total" id="oversea_total_order_price_front"><span class="title">Итого:</span><span class="value"><?=$arResult['PRINT_PRICE']?></span></div> 
                 <div class="result-val__nds">Сумма с НДС</div>
                 <div class="result-button">
                        <a href="/order/" class="button vbig_btn wides buy-btn"><span>Оформить заказ</span></a>
                  </div>  
            </div>
         <?else:?>
         <div class="h2" style="font-size: 30px;">Ваша корзина пуста</div>   
        <? endif; ?>
     </div>        
        </div>
    </div>
</div>