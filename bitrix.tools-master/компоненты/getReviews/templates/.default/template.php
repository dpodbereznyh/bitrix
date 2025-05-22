<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="reviews-container">
    <?php foreach ($arResult["ITEMS"] as $postId => $post) : ?>
        <div class="reviews-item">
            <div class="reviews-item__author-date-wrapper">
                <div class="reviews-item__author-name"><?= $post['COMMENT_AUTHOR_NAME']; ?></div>
                <div class="reviews-item__date"><?= $post['COMMENT_DATE']; ?></div>
            </div>
            <div class="reviews-item__rating">
                <div class="item-rating rating__star-svg--filled">
                    <?= TSolution::showSpriteIconSvg(SITE_TEMPLATE_PATH . "/images/svg/catalog/item_icons.svg#star-13-13", '', [
                        'WIDTH' => 16,
                        'HEIGHT' => 16,
                    ]); ?>
                </div>
                <div class="reviews-item__rating-value"><?= $post['COMMENT_RATING']; ?></div>
            </div>
            <div class="reviews-item__photo-wrapper">
                <a href="<?= $post['DETAIL_LINK']; ?>" class="reviews-item__photo"><img src="<?= $post['PREVIEW_PICTURE']; ?>" alt="<?= $post['TITLE']; ?>"></a>
            </div>
            <div class="reviews-item__info-container">
                <div class="reviews-item__name">
                    <a href="<?= $post['DETAIL_LINK']; ?>" class="dark_link"><?= $post['TITLE']; ?></a>
                </div>
                <div class="reviews-item__text"><?= $post['COMMENT_TEXT']; ?></div>
                <div class="reviews-item__text-more"><?= GetMessage("SHOW_MORE"); ?></div>
                <div class="reviews-item__link-product">
                    <a href="<?= $post['DETAIL_LINK']; ?>"><?= GetMessage("MORE_INFORMATION"); ?></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="reviews-pagination">
    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:main.pagenavigation",
        "",
        [
            "NAV_OBJECT" => $arResult['NAV'],
            "SEF_MODE" => "N",
        ],
        false
    );
    ?>
</div>