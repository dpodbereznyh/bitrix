<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/*
?>
<div class="xans-element- xans-order xans-order-basketpackage basket"> 
    <p class="orderStep"><img src="//img.echosting.cafe24.com/skin/base_en_US/order/img_order_step1.gif" alt="01 Cart"/></p>
                        <div class="orderListArea ec-base-table tblList">
                            <div class="xans-element- xans-order xans-order-normoverseatitle title">
                                <h3>Товаров <b><?=count($arResult['ITEMS'])?></b></h3>
                            </div>
                            <table class="xans-element- xans-order products-table basket">
                                <caption>Products</caption>
                                <colgroup>
                                    <col style="width:27px"/>
                                    <col style="width:auto"/>
                                    <col style="width:120px"/>
                                    <col style="width:75px"/>
                                    <col style="width:165px"/>
                                    <col style="width:110px"/>
                                    <col style="width:160px"/>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="chk" scope="col">
                                            <input type="checkbox" class="all_check"/>
                                        </th>
                                        <th scope="col">НАЗВАНИЕ</th>
                                        <th scope="col">ЦЕНА</th>
                                        <th scope="col">КОЛ-ВО</th>
                                        <th scope="col"></th>
                                        <th scope="col">ВСЕГО</th>
                                        <th scope="col">ВЫБОР</th>
                                    </tr>
                                </thead>
                                <tbody class="xans-element- xans-order xans-order-list center">
                                    <?foreach($arResult['ITEMS'] as $key=>$items):?>
                                    <tr class="xans-record-" data-id="<?=$items['ID']?>">
                                        <td class="select">
                                            <?if(!empty($items['PRICES']['PRICE'])):?>
                                            <input class="basket_chk" 
                                            id="basket_chk_id_<?=$key?>" 
                                            type="checkbox" 
                                            name="basket_product_normal_type_oversea" value="<?=$items['ID']?>"/>
                                            <?endif?>
                                        </td>
                                        <td class="subject"><a href="<?=$items['PRODUCT']['FIELDS']['DETAIL_PAGE_URL']?>">
                                            <div class="thumb"><img src="<?=$items['PREVIEW_PICTURE']['SRC']?>" alt="<?=$items['PRODUCT']['FIELDS']['NAME']?>"/></div>
                                            <div class="name"><?=$items['PRODUCT']['FIELDS']['NAME']?></div></a></td>
                                            <td class="price">
                                                <div class="mobile-label">Цена</div>
                                                <?//if($items['PRICES'][]):?>
                                                <?if($items['PRICES']['DISCOUNT_PRICE']!=0):?><div class="discount"><strong><?=$items['PRICES']['PRINT_BASE_PRICE']?></strong></div><?endif?>
                                                <div class="sellingPrice"><strong><?=$items['PRICES']['PRINT_PRICE']?></strong></div>
                                            </td>
                                            <td class="qty">
                                                 <?if(!empty($items['PRICES']['PRICE'])):?>
                                                <div class="mobile-label">Кол-во</div><span><span class="ec-base-qty">
                                                    <input class="cnt" id="quantity_id_<?=$items['ID']?>" name="quantity_name_<?=$items['ID']?>" size="2" value="<?=$items['QUANTITY']?>" type="text"/>
                                                    <a class="up"><img src="<?=SITE_TEMPLATE_PATH?>/images/btn_count_up.gif" alt="Up"/></a>
                                                    <a class="down"><img src="<?=SITE_TEMPLATE_PATH?>/images/btn_count_down.gif" alt="Down"/></a>
                                                    <?endif?>
                                                
                                            </td>
                                            <td>
                                                <div class="mobile-label"></div><span class="txtInfo" id="product_mileage0">-</span>
                                            </td>
                                            <td class="total">
                                                <div class="mobile-label">Всего</div>
                                                <span><?=$items['PRICES']['PRINT_FINAL_PRICE']?></span>
                                            </td>
                                            <td class="button" data-id="<?=$items['ID']?>">
                                                <?if(!empty($items['PRICES']['PRICE'])):?>
                                                <a class="btnNormal btn-order"><span class="icoCheck">Оформить</span></a>
                                                <a class="btnNormal btn-wish" data-js_favorite="<?=$items['PRODUCT']['FIELDS']['ID']?>" data-js_hide_button="Y"><span class="icoWish">Пожелания</span></a>
                                                <a class="btnNormal btn-del"><span class="icoClose">Удалить</span></a>
                                                <?else:?>
                                                <div class="gift-img"></div>
                                                <?endif?>
                                            </td>
                                        </tr>
                                        <?endforeach?>
                                    </tbody>
                                </table>
                                <div class="xans-element- xans-order xans-order-selectorder ec-base-button">
                                    <span class="gLeft">
                                        <a class="btnNormal sizeM btnGray btn-chk-del"><span class="icoDel">Удалить выделенные товары</span></a>
                                    <a class="btnNormal sizeM basket-clear">ОЧИСТИТЬ КОРЗИНУ</a></span>
                                    <span class="gRight">
                                        
                                        <a class="btnNormal sizeM" href="/catalog/">Продолжить покупки</a></span></div>
                                <div class="xans-element- xans-order xans-order-totaloversea ec-base-table tblList total">
                                    <table border="1" summary="">
                                        <caption>Total</caption>
                                        <thead>
                                            <tr>
                                                <th scope="col"><span>Стоимость товаров</span></th>
                                                <th id="oversea_total_benefit_price_title_area" scope="col">Скидка</th>
                                                <th scope="col">Полная стоимость</th>
                                            </tr>
                                        </thead>
                                        <tbody class="center">
                                            <tr>
                                                <td>
                                                    <div class="mobile-label">Стоимость товаров</div>
                                                    <div class="box txt16"><strong><span class="txt23"><span class="total_product_price_display_front"><?=$arResult['PRINT_BASE_PRICE']?></span></span></strong><span class="txt14 displaynone"><span class="total_product_price_display_back"></span></span></div>
                                                </td>
                                                <td id="oversea_total_benefit_price_area">
                                                    <div class="mobile-label">Скидка</div>
                                                    <div class="box txt16"><span class="iconMinus">-</span><strong><span class="txt23" id="oversea_total_product_discount_price_front"><?=$arResult['PRINT_DISCOUNT_PRICE']?></span></strong><span class="txt14 displaynone"><span id="oversea_total_product_discount_price_back"></span></span></div>
                                                </td>
                                                <td>
                                                    <div class="mobile-label">Полная стоимость</div>
                                                    <div class="box txtEm txt16"><span class="iconEquals">=</span><strong><span class="txt23" id="oversea_total_order_price_front"><?=$arResult['PRINT_PRICE']?></span></strong><span class="txt14 displaynone"><span id="oversea_total_order_price_back"></span></span></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="xans-element- xans-order xans-order-totalorder ec-base-button justify">
                                    <a class="btnBase buy-all">Оформить <b>Все</b></a>
                                    <a class="btnBase btnPoint buy-chk">Оформить выделенные <b> Товары</b></a></div>
                            </div>
                        </div>

                        <?*/

                    ?><script>
console.log(<?=json_encode($arResult, JSON_UNESCAPED_UNICODE)?>,'basket');
</script><? 

?>
<div class="wrapper_inner  wide_page cont-basket">
    <div class="middle">
        <div class="container">
            <div class="basket">
                <?if(!empty($arResult['ITEMS'])):?>
                <div class="img-block">
<?foreach($arResult['ITEMS'] as $key=>$items):?>
<?if($items['PREVIEW_PICTURE']['SRC']=='/bitrix/components/bitrix/sale.basket.basket/templates/.default/images/no_photo.png'){continue;}?>
                    <div class="preview-catalog" data-id="<?=$items['ID']?>" style="display: none;">
                    <img src="<?=$items['PREVIEW_PICTURE']['SRC']?>" alt="<?=$items['PRODUCT']['FIELDS']['NAME']?>"/></div>
                    <?endforeach?>
                </div>
    <div class="table">
    <div class="table-row pc-label">
        <div class="th-articul">
            Артикул
        </div>
        <div class="th-name">
            Наименование
        </div>
        <div class="th-weight">
            Вес
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
            <a href="<?=$items['PRODUCT']['FIELDS']['DETAIL_PAGE_URL']?>"><?=(!empty($items['PRODUCT']['PROPERTIES']['CML2_ARTICLE']['VALUE']))?$items['PRODUCT']['PROPERTIES']['CML2_ARTICLE']['VALUE']:"-"?></a>
        </div>
        <div class="td-name">
            <span class="td-name__img"><img src="<?=$items['PREVIEW_PICTURE']['SRC']?>" alt="<?=$items['PRODUCT']['FIELDS']['NAME']?>"/></span>
            <span class="name-val"><div class="mobile-label">Название: </div>
            
            <?=$items['PRODUCT']['FIELDS']['NAME']?></span>
        </div>
        <div class="td-weight "><div class="mobile-label">Вес: </div>
        <?if(!empty($items['PRODUCT']['CATALOG']['WEIGHT'])):?><?=$items['PRODUCT']['CATALOG']['WEIGHT']/1000?> кг.<?endif?></span>
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
           

        
         <?else:?>
         <div class="h2" style="font-size: 30px;">Ваша корзина пуста</div>   
        <? endif; ?>
     </div>        
        </div>
    </div>
</div>