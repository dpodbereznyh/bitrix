<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<div class="container">
    <div class="main-order cont-cent">
        
        <input type="hidden" name="PAYMENT_ID" value="1"/>

        <div class="main-order-left">
            <form id="basic-form" enctype="multipart/form-data">
        <div class="prop-name"><input type="text" name="PROPERTIES[COMPANY]" placeholder="Название компании*"
         value="<?=$arResult['PROPS']['COMPANY']['VALUE']?>" required/></div>
        <div class="prop-inn"><input type="text" name="PROPERTIES[INN]" placeholder="ИНН*" required/><?=$arResult['PROPS']['INN']['VALUE']?></div>
        <div class="prop-contact"><input type="text" name="PROPERTIES[CONTACT_PERSON]" placeholder="Контактное лицо*" required/><?=$arResult['PROPS']['CONTACT_PERSON']['VALUE']?></div>
        <div class="prop-phone"><input type="text" name="PROPERTIES[PHONE]" placeholder="Телефон*" required/><?=$arResult['PROPS']['PHONE']['VALUE']?></div>
        <div class="prop-email"><input type="email" name="PROPERTIES[EMAIL]" placeholder="E-Mail*" required/><?=$arResult['PROPS']['EMAIL']['VALUE']?></div>

        <div class="prop-shipment">
        <span>
            <input type="radio" name="SHIPMENT_ID" value="2" id="SHIPMENT_ID_2" 
            <?=(empty($_POST['SHIPMENT_ID'])||$_POST['SHIPMENT_ID']==2)?'checked':''?>/>
            <label for="SHIPMENT_ID_2">Самовывоз</label></span>
        <span><input type="radio" name="SHIPMENT_ID" value="1" id="SHIPMENT_ID_1" <?=($_POST['SHIPMENT_ID']==1)?'checked':''?>/><label for="SHIPMENT_ID_1">Доставка</label></span>
        </div>

        <div class="address-sclad"><span class="title">Адрес склада: </span>

            <span class="value">МО, г. Балашиха, ул. Белякова 1Б

             <?/*адреса складов можно внести здесь*/?>   
            </span>

        </div>
        <div class="prop-address hidden"><input type="text" name="PROPERTIES[ADDRESS]" placeholder="Адрес доставки*" value="<?=$arResult['PROPS']['ADDRESS']['VALUE']?>"/></div>
        <div class="prop-comments"><textarea name="USER_DESCRIPTION" placeholder="Комментарий"/><?=$arResult['USER_DESCRIPTION']['VALUE']?></textarea></div>
        
        <div class="prop-file"><input type="file" name="FILE" id="prop-file"/>
            <label for="prop-file">Прикрепить файл</label><span class="input-file-text"></span>
        </div>
        <div class="button-order"><a class="btn btn-default btn-lg">Оформить заказ</a></div>
        </form>
        </div>
   
        <div class="main-order-right">
            <div class="result-block">
                <div class="order-top">
                <div class="order-item">Ваш заказ</div>
                <div class="count-item"><span class="title">Кол-во товаров:</span><span class="value"><?=count($arResult['BASKET']['ITEMS'])?></span></div>
                <div class="weight-item"><span class="title">Вес:</span><span class="value"><?=round($arResult['BASKET']['WEIGHT']/1000)?> кг.</span></div>
                    </div>
                <div class="order-bottom">
                <div class="result-item"><span class="title">Итого:</span><span class="value"><?=$arResult['BASKET']['PRINT_PRICE']?></span></div>
                <div class="button-item"><a>Подтвердить заказ</a></div>
                    </div>
            </div>
        </div>

    </div>
</div>
<?
$APPLICATION->IncludeComponent(
    "forumedia:basket",
    "order",
    Array(
        'LID'=>'s1'
    )
);
?>
<?    