<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);
$RECAPTCHA_CLIENT_KEY=$arParams['RECAPTCHA_CLIENT_KEY'];
?>
    <div id="comm2" class="comment-modal hidden">
        <div class="modal-dialog">
            <form class="message-add" name="iblock_add" action="<?= POST_FORM_ACTION_URI ?>" method="post"
                  enctype="multipart/form-data">
            <div class="modal-content">
                <!-- Заголовок модального окна -->
                <div class="modal-header text-left">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="modal-title h4">Ваш отзыв</div>
                </div>
                <!-- Основное содержимое модального окна -->
                <div class="modal-body">
                    <?

                    if ( ! empty($arResult["ERRORS"])):?>
                        <? ShowError(implode("<br />", $arResult["ERRORS"])) ?>
                    <?endif;

                    if ($arResult["MESSAGE"] <> ''):?>
                        <?
                        ShowNote($arResult["MESSAGE"]) ?>
                    <?
                    endif ?>


                        <?= bitrix_sessid_post() ?>
                        <?
                        if ($arParams["MAX_FILE_SIZE"] > 0): ?><input type="hidden"
                                                                      name="MAX_FILE_SIZE"
                                                                      value="<?= $arParams["MAX_FILE_SIZE"] ?>"
                                                                       /><?
                        endif ?>
                        <?
                        /*Название*/ ?>
                        <input type="hidden" name="PROPERTY[NAME][0]"
                               value="product_<?= $arParams["ELEM_ID"] ?>"/>
                    <?/*Звезды*/?>
                    <div class="form-row row">
                        <div class="title-stars col-sm-12 col-md-4">Ваше впечатление *</div>
                        <div class="stars col-sm-6 col-md-4">
                            <div class="lbl-1 star-1 star-2 star-3 star-4 star-5 star"></div>
                            <div class="lbl-2 star-2 star-3 star-4 star-5 star"></div>
                            <div class="lbl-3 star-3 star-4 star-5 star"></div>
                            <div class="lbl-4 star-4 star-5 star"></div>
                            <div class="lbl-5 star-5 star"></div>
                        </div>
                        <div class="info-stars col-sm-5 col-md-4">Не выбрано</div>
                    </div>
                    <input type="hidden" name="PROPERTY[148][0]" class="input-stars" value="" required/>
                    <div class="form-row row">
                            <div>
                                <label for="comment">Комментарий *</label>
                            </div>
                            <div>
            <textarea id="fcomment" rows="4" name="PROPERTY[149][0]" width="100%"
                      required placeholder="Опишите подробнее свои впечатления"></textarea>
                            </div>
                    </div>

                    <div class="form-row row drug-drop">
                        <div class="col-12">
                            <div class=""></div>
                            <div><input type="hidden" name="PROPERTY[144][0]" value=""/>
                            <input id="send_0" type="file" name="PROPERTY_FILE_144_0" accept="image/*"/></div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-row col-12 col-sm-6">
                            <label for="fname">Как вас зовут? *</label>
                            <input id="fname" type="text" name="PROPERTY[150][0]" size="30"
                                   value=""/>
                        </div>
                        <div class="form-row col-12 col-sm-6">
                            <label for="fcity">Откуда вы?</label><input id="fcity" type="text"
                                                                        name="PROPERTY[151][0]"
                                                                        size="30" value=""/>
                        </div>
                    </div>
<div class="form-row row">
                        <div class="col-12">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="g-recaptcha" data-sitekey="<?=$RECAPTCHA_CLIENT_KEY?>">
							</div>
</div>

					</div>
                    <?
                    /*Негативные отзывы*/ ?><input type="hidden" name="PROPERTY[147][0]" value="0"/>
                    <?
                    /*Позитивные отзывы*/ ?><input type="hidden" name="PROPERTY[146][0]" value="0"/>
                    <? /*Элемент каталога*/ ?>
                    <input type="hidden" name="PROPERTY[145][0]"
                           value="<?= $arParams["ELEM_ID"] ?>"/>
                    <div class="form-row row">
                        <div class="col-12">
                            <input id="access" type="checkbox" required name="access"
                                   value="Y"><label for="access">Я согласен на <a
                                        href="/include/licenses_detail.php" target="_blank">обработку
                                    персональных данных</a></label>
                        </div>
                    </div>

                    </div>
                <!-- Футер модального окна -->
                <div class="modal-footer">
                    <div class="form-row row">
                        <div class="col-12">
                            <input type="submit" class="btnNormal write-btn" name="iblock_submit"
                                   value="Отправить"/>
                        </div>
                    </div>
                </div>
            </div>

            </form>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('body').on('click','.comment-modal .close',function(e){
                $('.comment-modal').addClass('hidden');
                $('.wrapper1 .header_wrap').css('z-index','14');
            });

            $('body').on('click','.comment-modal .modal-dialog',function (e){
                e.stopPropagation();
            })
            /*if($('.comment-modal').hasClass('hidden'))
            {
                $('.comment-modal').removeClass('hidden');
                $('.wrapper1 .header_wrap').css('z-index','4');
            }*/
        });

    </script>
<?php