<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$this->addExternalCss("/local/components/dpdev/anchor_navigation/templates/.default/styles.css");
$this->addExternalJS("/local/components/dpdev/anchor_navigation/templates/.default/scripts.js");
?>
<?
echo $arResult['TEST'];
?>
<div class="doctors-carusel" id="docs">
    <div class="center-wrap --disable">
        <div class="doctors-carusel__wrap">
            <div class="type-carusel js-swiper__doctors">
                <div class="type-carusel__head flex --align-center --just-space mb-18">
                    <div class="type-carusel__title">Более 50 ведущих стоматологов<br> в Москве и Московской области</div>
                    <div class="type-carusel__nav flex --align-center">
                        <div class="type-carusel__pagination">
                            <div class="type-carusel__btn --prev --light swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                            <div class="type-carusel__btn --next --light swiper-button-next"></div>
                        </div>
                        <div class="type-carusel__show-all-wrap flex mob-hide ">
                            <a href="/doctors/" class="type-carusel__show-all btn --border-1"><span>Выбрать врача</span></a>
                        </div>
                    </div>
                </div>
                <div class="type-carusel__swiper">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <? foreach ($arResult["DOCTORS"] as $arDoctor) : ?>
                                <div class="swiper-slide">
                                    <div class="doctor-card">
                                        <?
                                        if ($arDoctor['PREVIEW_PICTURE']) {
                                            $renderImage = CFile::ResizeImageGet(
                                                $arDoctor["PREVIEW_PICTURE"],
                                                array(
                                                    "height" => 400,
                                                    "width" => 400
                                                ),
                                                BX_RESIZE_IMAGE_PROPORTIONAL
                                            );

                                            $renderImage = Pict::getWebp($renderImage);
                                            $bgimage = ($renderImage["WEBP_SRC"] != '') ? $renderImage["WEBP_SRC"] : $renderImage['src'];
                                        } else {
                                            if ($arDoctor["PROPERTY_GENDER_VALUE"] == 'Мужской') {
                                                $bgimage = SITE_TEMPLATE_PATH . '/img/no-photo--male.svg';
                                            }
                                            if ($arDoctor["PROPERTY_GENDER_VALUE"] == 'Женский') {
                                                $bgimage = SITE_TEMPLATE_PATH . '/img/no-photo--female.svg';
                                            }
                                        }
                                        ?>
                                        <a href="<?= $arDoctor["DETAIL_PAGE_URL"] ?>" class="doctor-card__photo lazy" data-background-image="<?= $bgimage; ?>">
                                            <div class="doctor-card__medal --svg__doctors--medal"></div>
                                        </a>
                                        <div class="doctor-card__info px-24 pb-24">
                                            <!-- <div class="doctor-card__devider devider --top --min" style="background-color: white;"></div> -->
                                            <div class="doctor-card__top">
                                                <div class="doctor-card__rate flex --align-center">
                                                    <div class="review-card__rate-star --svg__reviews--star-gold"></div>
                                                    <div class="review-card__rate-star --svg__reviews--star-gold"></div>
                                                    <div class="review-card__rate-star --svg__reviews--star-gold"></div>
                                                    <div class="review-card__rate-star --svg__reviews--star-gold"></div>
                                                    <div class="review-card__rate-star --svg__reviews--star-gold"></div>
                                                </div>
                                                <a href="<?= $arDoctor["DETAIL_PAGE_URL"] ?>" class="doctor-card__name p --l link --color-dark"><?= $arDoctor["NAME"]; ?></a>
                                                <div class="doctor-card__value p --m -green"><?= $arDoctor["PROPERTY_DIGNITY_VALUE"]; ?></div>
                                                <!-- <div class="doctor-card__type p --m"><?= $arDoctor["PROPERTY_PRESENT_POST_VALUE"]; ?></div> -->
                                                <div class="doctor-card__type p --m">
                                                    <?
                                                    // Подсчитать количество элементов в массиве
                                                    $total = count($arDoctor["PROPERTY"]["PRESENT_POST"]["VALUE"]);
                                                    // Создаем счетчик
                                                    $i = 0;
                                                    // Перебираем массив должностей врача
                                                    foreach ($arDoctor["PROPERTY"]["PRESENT_POST"]["VALUE"] as $item) :
                                                        // Выводим должности врача через запятую
                                                        echo (++$i === $total) ? $item : $item . ", ";
                                                    endforeach; ?>

                                                </div>
                                                <div class="doctor-card__values mt-4">
                                                    <? if (!empty($arDoctor["PROPERTY_EXPERIENCE_VALUE"])) : ?>
                                                        <div class="doctor-card__value p --m">Стаж: <b><?= $arDoctor["PROPERTY_EXPERIENCE_VALUE"]; ?></b></div>
                                                    <? endif; ?>
                                                </div>
                                                <? if (!empty($arResult['CLINICS'][$arDoctor['PROPERTY_CLINICS_VALUE']]['PROPERTY_SHORT_NAME_VALUE'])) : ?>
                                                    <div class="doctor-card__values mt-4">
                                                        <div class="doctor-card__value p --m">Филиал:
                                                            <b><?=$arResult['CLINICS'][$arDoctor['PROPERTY_CLINICS_VALUE']]['PROPERTY_SHORT_NAME_VALUE']?></b></div>
                                                    </div>
                                                <?elseif (!empty($arResult['CLINICS'][$arDoctor['PROPERTY_CLINICS_VALUE']]['NAME'])):?>
                                                    <div class="doctor-card__values mt-4">
                                                        <div class="doctor-card__value p --m">Филиал:
                                                            <b><?=$arResult['CLINICS'][$arDoctor['PROPERTY_CLINICS_VALUE']]['NAME']?></b></div>
                                                    </div>
                                                <? endif; ?>
                                                <div class="doctor-card__values mt-4">
                                                    <?
                                                    $docId = $arDoctor["ID"];
                                                    $reviws = [];
                                                    $resReviews = CIBlockElement::GetList(["SORT" => "NAME"], ["IBLOCK_ID" => "5", "PROPERTY_DOCTOR" => $docId], false, false, ["NAME", "ID", "PROPERTY_DOCTOR"]);
                                                    while ($review = $resReviews->GetNext()) {
                                                        $reviws[] = $review;
                                                    }
                                                    ?>
                                                    <div class="doctor-card__value p --m">Отзывов о враче: <b><?= count($reviws) ?></b></div>
                                                </div>
                                            </div>
                                            <div class="doctor-card__btns flex --align-center --just-space mt-24">
                                                <a href="#feedback" data-popup="#feedback" data-popup-title="" data-popup-value="<?= $arDoctor["NAME"]; ?>" class="doctor-card__more btn --fill-1 "><span class="--p">Записаться онлайн</span></a>
                                                <? /* <a class="btn-link doctor-card__link" href="<?=$arDoctor["DETAIL_PAGE_URL"]?>">Подробнее</a> */ ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="mob-show">
                    <div class="type-carusel__pagination">
                        <div class="type-carusel__btn --prev  swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                        <div class="type-carusel__btn --next  swiper-button-next"></div>
                    </div>
                    <a href="/doctors/" class="doctors__pagination load-more btn --border-1 --p mt-16 mb-16"><span class="p --l">Выбрать врача</span></a>
                </div>
            </div>
        </div>
    </div>
</div>