<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

                <div class="catalog-stars">
                    <div class="stars">
                        <div class="<?= ($arResult['STARS'] > 0) ? 'star-act'
                            : 'star' ?>"></div>
                        <div class="<?= ($arResult['STARS'] > 1) ? 'star-act'
                            : 'star' ?>"></div>
                        <div class="<?= ($arResult['STARS'] > 2) ? 'star-act'
                            : 'star' ?>"></div>
                        <div class="<?= ($arResult['STARS'] > 3) ? 'star-act'
                            : 'star' ?>"></div>
                        <div class="<?= ($arResult['STARS'] > 4) ? 'star-act'
                            : 'star' ?>"></div>
                    </div>
                    <div class="info-stars"><?= (!empty($arResult['STARS_INFO']))?$arResult['STARS_INFO']:'Нет отзывов'; ?></div>
                </div>


