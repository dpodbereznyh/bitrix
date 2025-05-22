<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<div class="xans-element- xans-order xans-order-basketpackage basket"> 
    <p class="orderStep"><img src="//img.echosting.cafe24.com/skin/base_en_US/order/img_order_step1.gif" alt="01 Cart"/></p>
                    <?/*<div class="xans-element- xans-order xans-order-dcinfo ec-base-box memberBox typeMember">
                        <div class="information">
                            <h3 class="title">BENEFITS</h3>
                            <div class="description">
                                <div class="member">
                                    <p><b>Natasha</b> your membership level is <b class="pointColor">WHITE</b>.</p>
                                    <ul>
                                        <li class="displaynone">Receive an <span> Additional discount of </span> when you purchase <span class="displaynone">0 or more. </span><span class="displaynone"></span><span class="displaynone">(Up to 0 off)</span></li>
                                        <li>
                                            | Receive
                                            span Additional reward points of 1%
                                            | when you purchase
                                            span.displaynone 0 or more.
                                            span.displaynone
                                            span (Up to USD 1.00 off)
                                        </li>
                                    </ul>
                                </div>
                                <ul class="mileage">
                                    <li><a href="/myshop/mileage/historyList.html">Available credit <b class="pointColor">0＄</b></a></li>
                                    <li class="displaynone"><a href="/myshop/deposits/historyList.html">deposit : <strong></strong></a></li>
                                    <li><a href="/myshop/coupon/coupon.html">Coupons <b class="pointColor">3 coupon(s)</b></a></li>
                                </ul>
                            </div>
                        </div>
                        </div>*/?>
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

                        <?