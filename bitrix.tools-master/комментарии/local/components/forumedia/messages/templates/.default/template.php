<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
    <script>
        var C_IBLOCK_ID=<?=$arParams['IBLOCK_ID'];?>;
        var C_ID=<?=$arParams['ID'];?>;
        var C_PATH="<?=$templateFolder;?>";
    </script>
<?/*
    <div class="comments-block">
        <div class="h2">Отзывы покупателей</div>

        <div class="comments-list">
            <div class="row">
                <div class="comments-titel-left col-md-6 col-sm-12">
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
                    <div class="info-stars"><?= $arResult['STARS_INFO'] ?></div>
                    <?if(empty($arResult['STARS_INFO'])):?><div class="h4 info-stars">У товара пока нет отзывов</div><?endif?>
                </div>

                <div class="comments-titel-right col-md-6 col-sm-12 ">
                    <div class="btn btn-gray comment-sort "
                         data-sort="<?= (isset($_REQUEST['commentSort'])
                             && $_REQUEST['commentSort'] == 'desc') ? 'asc'
                             : 'desc' ?>">По полезности
                    </div>
                    <div class="btn btn-default js-add-comment ">Написать отзыв
                    </div>
                </div>
            </div>
            <div class="comments-block-ajax">
            <?php
            $RANK = false;
            if ($_REQUEST['AJAX'] == 'Y') {
                $GLOBALS['APPLICATION']->RestartBuffer();
            }
            foreach ($arResult['ITEMS'] as $item) {
                ?>
                <div class="comment-item" id="<?=$item["AREA_ID"]?>">
                    <div class="comment-item_titel"><span
                                class="comment-item_name"><?= $item["PROPERTY_NAME_VALUE"]; ?></span><span
                                class="comment-item_city"><?= $item["PROPERTY_CITY_VALUE"]; ?></span><span
                                class="comment-item_date"><?= $item["DATE"]; ?></span>
                    </div>
                    <div class="comment-item_stars">
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 0)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 1)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 2)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 3)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 4)
                            ? 'star-act' : 'star' ?>"></div>
                    </div>
                    <div class="comment-item_text"><?= $item['~PROPERTY_MESSAGE_VALUE'] ?></div>
                    <div class="comment-item_images">
                        <? foreach ($item['PROPERTY_FILES_VALUE'] as $file){ ?>
                        <?
                        $image = CFile::ResizeImageGet($file,
                            array('height' => '70', "width" => '70'),
                            BX_RESIZE_IMAGE_EXACT,true);
                        if (isset($image['src'])) {
                            ?>
                            <div class="comment-item_img"><img
                                        src="<?= $image['src'] ?>"
                                        height="<?= $image['height'] ?>"
                                        width="<?= $image['width'] ?>"/></div>
                        <?
                        }} ?>

                    </div>
                    <div class="block-plus-minus">
                        <div class="icon-plus" data-id="<?=$item['ID']?>"></div>
                        <span class="comment-item_count-plus"><?= $item['PROPERTY_POSITIVE_VALUE'] ?></span>
                        <div class="icon-minus" data-id="<?=$item['ID']?>"></div>
                        <span class="comment-item_count-minus"><?= $item['PROPERTY_NEGATIVE_VALUE'] ?></span>
                    </div>
                </div>
            <?
            } ?>
            <?
            if (isset($arParams['PAGE'])): ?>
                <?
                $rank = $arParams['PAGE']['nPageSize']
                    * ($arParams['PAGE']['iNumPage'] - 1)
                    + count($arResult['ITEMS']);
                ?>
                <?
                if ($rank < $arResult['COUNT']): ?>
                    <div class="btn btn-gray comment-more"
                         data-page="<?= $arParams['PAGE']['iNumPage'] + 1 ?>"
                         data-psize="<?= $arParams['PAGE']['nPageSize'] ?>" 
						 data-sort="<?= isset($_REQUEST['commentSort']) ? $_REQUEST['commentSort']: '' ?>">Еще
                        отзывы
                    </div>
                <?
                endif ?>
            <?
            endif ?>

            <? if ($_REQUEST['AJAX'] == 'Y'): ?>
                <? die(); ?>
            <?endif ?>
            </div>
        </div>
    </div>*/?>

    <p class="desc">Оставьте здесь свой отзыв.</p>
	<div class="xans-board xans-board-listpackage">
		<div class="ec-base-table typeList gBorder tblReview">
			<table border="1" summary="">
				<caption>Отзывы о товаре</caption>
				<colgroup>
					<col style="width: 5%"/>
					<col style="width: 50%"/>
					<col style="width: 15%"/>
					<col style="width: 15%"/>
					<col style="width: 15%"/>
				</colgroup>
				<thead class="xans-element- xans-board xans-board-listheader-4 xans-board-listheader xans-board-4">
					<tr>
						<th scope="col">№</th>
						<th scope="col">Отзыв</th>
						<th scope="col">Пользователь</th>
						<th scope="col">Дата</th>
						<th scope="col">Нравится</th>
					</tr>
				</thead>
				<tbody class="comments-block-ajax">
            <?php
            $RANK = false;
            if ($_REQUEST['AJAX'] == 'Y') {
                $GLOBALS['APPLICATION']->RestartBuffer();
            }?>
					<?foreach($arResult['ITEMS'] as $key=>$item):?>
					<tr id="<?=$item["AREA_ID"]?>" class="xans-record- reviews-post-table">
				<td class="num"><?=$key+1?></td>
				<td class="subject">
					<div class="mobile-label">Отзыв</div>
					<div data-bx-role="text" class="reviews-text" id="message_text_<?=$res["ID"]?>">
						<?= $item['~PROPERTY_MESSAGE_VALUE'] ?></div>

						<? foreach ($item['PROPERTY_FILES_VALUE'] as $file){ ?>
                        <?
                        $image = CFile::ResizeImageGet($file,
                            array('height' => '100', "width" => '100'),
                            BX_RESIZE_IMAGE_EXACT,true);
                        if (isset($image['src'])) {
                            ?>
                            <div class="comment-item_img"><img
                                        src="<?= $image['src'] ?>"
                                        height="<?= $image['height'] ?>"
                                        width="<?= $image['width'] ?>"/></div>
                        <?
                        }} ?>
							</td>
							<td>
								<div class="mobile-label">Пользователь</div>
								<?= $item["PROPERTY_NAME_VALUE"]; ?>, <?= $item["PROPERTY_CITY_VALUE"]; ?>
							</td>
							<td>
								<div class="mobile-label">Дата</div><span class="txtNum"><?= $item["DATE"]; ?></span>
							</td>
							<td>
								<div class="mobile-label">Рейтинг</div>
								<span class="txtNum reviews-button-small rating_vote_text">
									<div class="comment-item_stars">
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 0)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 1)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 2)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 3)
                            ? 'star-act' : 'star' ?>"></div>
                        <div class="<?= ($item['PROPERTY_STARS_VALUE'] > 4)
                            ? 'star-act' : 'star' ?>"></div>
                    </div>
									</span>
								</td>	
							</tr>
							<?endforeach;?>
				<?	if (isset($arParams['PAGE'])): ?>
                <?
                $rank = $arParams['PAGE']['nPageSize']
                    * ($arParams['PAGE']['iNumPage'] - 1)
                    + count($arResult['ITEMS']);
                ?>
                <?
                if ($rank < $arResult['COUNT']): ?>
                    <tr><div class="btnNormal comment-more"
                         data-page="<?= $arParams['PAGE']['iNumPage'] + 1 ?>"
                         data-psize="<?= $arParams['PAGE']['nPageSize'] ?>" 
						 data-sort="<?= isset($_REQUEST['commentSort']) ? $_REQUEST['commentSort']: '' ?>">Еще
                        отзывы
                    </div></tr>
                <?
                endif ?>
            <?
            endif ?>

            <? if ($_REQUEST['AJAX'] == 'Y'): ?>
                <? die(); ?>
            <?endif ?>		
						</tbody>
					</table>
				</div>
			</div>
			<p class="ec-base-button typeBorder">
				<span class="gRight">
					<a href="/reviews/" class="btnNormal">Все отзывы</a>
					<a class="btnNormal write-btn js-add-comment">Написать отзыв</a>
				</span></p>


			<?php

		?></div>


<?php
$APPLICATION->IncludeComponent("forumedia:iblock.element.add.form", "add_request",
    array(
        "SEF_MODE"                      => "N",
        "IBLOCK_ID"                     => $arParams['IBLOCK_ID'],
        "PROPERTY_CODES"                => isset($arParams['PROPERTY_CODES'])
            ? $arParams['PROPERTY_CODES'] : array(),
        "PROPERTY_CODES_REQUIRED"       => isset($arParams['PROPERTY_CODES_REQUIRED'])
            ? $arParams['PROPERTY_CODES_REQUIRED'] : array(),
        "GROUPS"                        => array("2"),
        "STATUS_NEW"                    => "N",
        "STATUS"                        => array("ANY"),
        "LIST_URL"                      => "",
        "MAX_USER_ENTRIES"              => "100000",
        "MAX_LEVELS"                    => "100000",
        "LEVEL_LAST"                    => "Y",
        "USE_CAPTCHA"                   => "N",
        "USER_MESSAGE_EDIT"             => "",
        "USER_MESSAGE_ADD"              => "",
        "DEFAULT_INPUT_SIZE"            => "30",
        "RESIZE_IMAGES"                 => "Y",
        "MAX_FILE_SIZE"                 => 1024 * 1024 * 1024 * 10,
        "PREVIEW_TEXT_USE_HTML_EDITOR"  => "Y",
        "DETAIL_TEXT_USE_HTML_EDITOR"   => "Y",
        "CUSTOM_TITLE_NAME"             => "",
        "CUSTOM_TITLE_TAGS"             => "",
        "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
        "CUSTOM_TITLE_DATE_ACTIVE_TO"   => "",
        "CUSTOM_TITLE_IBLOCK_SECTION"   => "",
        "CUSTOM_TITLE_PREVIEW_TEXT"     => "",
        "CUSTOM_TITLE_PREVIEW_PICTURE"  => "",
        "CUSTOM_TITLE_DETAIL_TEXT"      => "",
        "CUSTOM_TITLE_DETAIL_PICTURE"   => "",
        "ELEM_ID"                       => $arParams['ID'],
        "SEF_FOLDER"                    => "/",
        "VARIABLE_ALIASES"              => array(),
        "RECAPTCHA_SERVER_KEY"=>"6LcjJqUiAAAAAEJZGK5i437WF7I4-HKzcTcCfbN5",
        "RECAPTCHA_CLIENT_KEY"=>"6LcjJqUiAAAAAE2ARtbw--r1biGgg_DFOiG0jKwV",
        'CATALOG_PROPERTY_COUNT'=>'vote_count',
        'CATALOG_PROPERTY_SUM'=>'vote_sum',
        'CATALOG_PROPERTY_RATING'=>'rating',
        'ID_PROPERTY_STARS'=>'148'
    ),
    $component
);


