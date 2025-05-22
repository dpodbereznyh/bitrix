<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

                <div class="xans-element- xans-myshop xans-myshop-wishlist ec-base-table tblList xans-record- favorite">
                    <div class="title center">
                        <h3>Список пожеланий</h3>
                    </div>
                    <table class="xans-element- xans-order products-table">
                        <caption>Products</caption>
                        <colgroup>
                            <col style="width:auto"/>
                            <col style="width:120px"/>
                            <col style="width:75px"/>
                            
                            <col style="width:160px"/>
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">НАЗВАНИЕ</th>
                                <th scope="col">ЦЕНА</th>
                                <th scope="col"></th>
                                
                                <th scope="col">ВЫБОР</th>
                            </tr>
                        </thead>
                        <tbody class="xans-element- xans-order xans-order-list center">
                            <?foreach($arResult['ITEMS'] as $items):?>
                            <?if($items['PRICES']['PRICE']==0){continue;}?>
                            <tr class="xans-record-" data-id="<?=$items['ID']?>">
                                <td class="subject"><a href="<?=$items['PRODUCT']['FIELDS']['DETAIL_PAGE_URL']?>">
                                        <div class="thumb"><img src="<?=$items['PREVIEW_PICTURE']['SRC']?>" alt="<?=$items['PRODUCT']['FIELDS']['NAME']?>"/></div>
                                        <div class="name"><?=$items['PRODUCT']['FIELDS']['NAME']?></div></a></td>
                                <td class="price">
                                    <div class="mobile-label">Цена</div>
                                    <?if($items['PRICES']['DISCOUNT_PRICE']!=0):?><div class="discount">
                                        <strong><?=$items['PRICES']['PRINT_BASE_PRICE']?></strong></div><?endif?>
                                                <div class="sellingPrice"><strong><?=$items['PRICES']['PRINT_PRICE']?></strong></div>
                                </td>
                                <td>
                                    <div class="mobile-label"></div><span class="txtInfo" id="product_mileage0">-</span>
                                </td>
                                
                                <td class="button" data-id="<?=$items['ID']?>">
                                    <a class="btnNormal btn-order"><span class="icoCheck">Оформить</span></a>
                                    <a class="btnNormal btn-bask" data-js_basket="<?=$items['PRODUCT']['FIELDS']['ID']?>" data-js_hide_button="Y"><span class="icoWish">Корзина</span></a>
                                    <a class="btnNormal btn-del"><span class="icoClose">Удалить</span></a></td>
                            </tr>
                            <?endforeach?>
                        </tbody>
                    </table>
                </div>

<?