<?
  if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<?
  if (
    isset($arResult["BASKET_ITEMS"]) &&
    is_array($arResult["BASKET_ITEMS"]) &&
    count($arResult["BASKET_ITEMS"])
  ):
    ?>

    <section class="new-order new-order__step-one">
      <div class="container new__container">
        <div class="new-order__content">
          <div class="new-order__title">
            <h1 class="block-title">Оформление заказа</h1>
          </div>

          <ul class="new-order__list">
            <li class="new-order__item active">
              <div class="new-order__check" aria-label="Контактные данные">
                <span class="new-order__counter"></span>
                <span>Контактные данные</span>
              </div>
              <span class="new-order__separator"></span>
            </li>


            <li class="new-order__item">
              <div class="new-order__check">
                <span class="new-order__counter"></span>
                <span>Доставка</span>
              </div>
              <span class="new-order__separator"></span>
            </li>

            <li class="new-order__item">
              <div class="new-order__check">
                <span class="new-order__counter"></span>
                <span>Оплата</span>
              </div>
            </li>
          </ul>
        </div>

        <div class="new-order__inner">
          <div class="new-order__left">
            <form class="new-order__form"
                  method="post"
                  name="new__order__step__one"
                  action="/"
                  id="new-order-step-one"
            >
              <div class="new-order__div">
                <label class="new-order__label" for="new-order-fields-name">
                  ФИО получателя
                </label>
                <div class="new-order__inputs">
                  <input class="new-order__fields new-order__required" type="text" id="new-order-fields-name"
                         placeholder="ФИО получателя"/>
                </div>
              </div>

              <div class="new-order__div">
                <label class="new-order__label" for="new-order-fields-phone">
                  Контактный телефон получателя
                </label>
                <div class="new-order__inputs">
                  <input class="new-order__fields new-order__required" type="tel" id="new-order-fields-phone"
                         placeholder="+7 888 888-88-88"/>
                </div>
              </div>
              <div class="new-order__div">
                <button class="new-order__submit" aria-label="К выбору доставки" type="submit">
                  <span>К выбору доставки</span>
                </button>
              </div>
            </form>
          </div>
          <div class="new-order__right">
            <div class="new-order__box">
              <div class="new-order__block" aria-expanded="false">
                <div class="new-order__right-title">
                  <strong class="new-order__strong">2 товара</strong>
                  <span class="new-order__total"><?= priceFormat($arResult["ORDER_PRICE"]) ?> ₽</span>
                </div>
                <button class="new-order__icon" type="button" aria-label="стрелка" data-action="order-accordion"
                        aria-expanded="false">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="none">
                    <path d="M13.2634 22L19.6317 15.6317L26 22" stroke="currentColor" stroke-width="2"
                          stroke-linecap="round"/>
                  </svg>
                </button>
              </div>
              <div class="new-order__height" aria-expanded="true" data-action="new-order-height">
                <?
                  foreach ($arResult["BASKET_ITEMS"] as $arBasketItem):

                    if ($arBasketItem["PREVIEW_PICTURE_ID"])
                      $imageFileId = $arBasketItem["PREVIEW_PICTURE_ID"];

                    ?>
                    <div class="new-order__order">
                      <?
                        if (isset($imageFileId)):

                          $imageFileSrc = CFile::ResizeImageGet(
                            $imageFileId,
                            ["width" => 34, "height" => 99],
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                          )["src"];
                          ?>
                          <i class="new-order__thumbs"
                             style="background-image: url('<?= $imageFileSrc ?>');"></i>
                        <? endif; ?>
                      <div class="new-order__wrapper">
                        <strong class="new-order__wrapper-strong">
                          <?= $arBasketItem["NAME"]; ?>
                        </strong>
                        <div class="new-order__wrapper-box">
                          <span class="new-order__amount">1 шт</span>
                          <div class="new-order__wrapper-block">
                            <?

                              $basePrice = $arBasketItem["OPTIMAL_PRICE"]["RESULT_PRICE"]["BASE_PRICE"];
                              $discountPrice = $arBasketItem["OPTIMAL_PRICE"]["RESULT_PRICE"]["DISCOUNT_PRICE"];

                              if (
                                $basePrice != $discountPrice
                              ):
                                ?>
                                <strong class="new-order__wrapper-new">
                                  <?= priceFormat($discountPrice); ?> ₽
                                </strong>
                                <span class="new-order__wrapper-old">
                                <?= priceFormat($basePrice); ?> ₽
                              </span>
                              <? else: ?>
                                <strong class="new-order__wrapper-new">
                                  <?= priceFormat($basePrice); ?> ₽
                                </strong>
                              <? endif; ?>

                          </div>
                        </div>
                      </div>
                    </div>
                    <?
                    if (isset($imageFileId))
                      unset($imageFileId);

                  endforeach;
                ?>

                <div class="new-order__summary">
                  <dl class="new-order__total-box">
                    <div class="new-order__total-div">
                      <dt class="new-order__total-div--appellation">
                        Товаров на
                      </dt>
                      <dd class="new-order__total-div--number"><?= priceFormat($arResult["ORDER_PRICE"]) ?> ₽</dd>
                    </div>
                    <div class="new-order__total-div">
                      <dt class="new-order__total-div--appellation">
                        Общий вес
                      </dt>
                      <dd class="new-order__total-div--number"><?= priceFormat($arResult["ORDER_WEIGHT"]); ?> г</dd>
                    </div>
                    <? if ($arResult["ORDER_DISCOUNT"]): ?>
                      <div class="new-order__total-div">
                        <dt class="new-order__total-div--appellation">
                          Экономия
                        </dt>
                        <dd class="new-order__total-div--number">
                          <?= priceFormat($arResult["ORDER_DISCOUNT"]); ?> ₽
                        </dd>
                      </div>
                    <? endif; ?>
                    <div class="new-order__total-div">
                      <dt class="new-order__total-div--small">Итого</dt>
                      <dd class="new-order__total-div--bold"><?= priceFormat($arResult["ORDER_PRICE"]) ?>₽</dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  <? else: ?>

    <section class="new-order new-order__step-one">
      <div class="container new__container">
        <p>
          Отсутствуют товары в корзине.<br/>
          <a href="/catalog/omron/">Перейти в каталог</a>
        </p>
      </div>
    </section>

  <? endif; ?>