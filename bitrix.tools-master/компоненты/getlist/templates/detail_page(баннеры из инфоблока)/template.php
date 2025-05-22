<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));

// if ($USER->IsAdmin()) {
//     ob_start();
//     var_export($arResult);
//     $output = ob_get_clean();
//     file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/log_getlist.txt', $output, LOCK_EX);
// };

if (!empty($arResult)) {
?>
    <div class="layouts-container">
        <? foreach ($arResult as $key => $layout) : ?>
            <?
            if ($layout["PROPERTIES"]["BACKGROUND_LAYOUT"]["VALUE"] !== '') {
                $background = '#' . $layout["PROPERTIES"]["BACKGROUND_LAYOUT"]["VALUE"];
            } else {
                $background = '#FFFFFF';
            }
            if ($layout["PROPERTIES"]["TEXT_COLOR"]["VALUE"] !== '') {
                $textColor = '#' . $layout["PROPERTIES"]["TEXT_COLOR"]["VALUE"];
            } else {
                $textColor = '#2D2E31';
            }
            ?>
            <? if ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "1") : ?>
                <div class="layout-item layout-item--type-1" style="background-color: <?= $background ?>;">
                    <div class="layout-item__text-container">
                        <div class="layout-item__title" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?></div>
                        <div class="layout-item__text" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TEXT_LAYOUT"]["~VALUE"][0]["TEXT"]; ?></div>
                    </div>
                    <div class="layout-item__picture">
                        <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][0]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>">
                    </div>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "2") : ?>
                <div class="layout-item layout-item--type-2" style="background-color: <?= $background ?>;">
                    <div class="layout-item__picture">
                        <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][0]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>">
                    </div>
                    <div class="layout-item__text-container">
                        <div class="layout-item__title" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?></div>
                        <div class="layout-item__text" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TEXT_LAYOUT"]["~VALUE"][0]["TEXT"]; ?></div>
                    </div>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "3") : ?>
                <div class="layout-item layout-item--type-3" style="background-color: <?= $background ?>;">
                    <? foreach ($layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"] as $keyPart => $layoutPart) : ?>
                        <div class="layout-item__container--type-3">
                            <div class="layout-item__picture">
                                <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][$keyPart]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][$keyPart]; ?>">
                            </div>
                            <div class="layout-item__text-container">
                                <div class="layout-item__title" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][$keyPart]; ?></div>
                                <div class="layout-item__text" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TEXT_LAYOUT"]["~VALUE"][$keyPart]["TEXT"]; ?></div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "4") : ?>
                <div class="layout-item layout-item--type-4" style="background-color: <?= $background ?>;">
                    <div class="layout-item__picture">
                        <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][0]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>">
                    </div>
                    <div class="layout-item__text-container">
                        <div class="layout-item__title" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?></div>
                        <div class="layout-item__text" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TEXT_LAYOUT"]["~VALUE"][0]["TEXT"]; ?></div>
                    </div>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "5") : ?>
                <div class="layout-item layout-item--type-5" style="background-color: <?= $background ?>;">
                    <div class="layout-item__picture">
                        <? foreach ($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"] as $keyPart => $layoutPart) : ?>
                            <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][$keyPart]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>">
                        <? endforeach; ?>
                    </div>
                    <div class="layout-item__text-container">
                        <div class="layout-item__title" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?></div>
                    </div>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "6") : ?>
                <div class="layout-item layout-item--type-6" style="background-color: <?= $background ?>;">
                    <div class="layout-item__picture">
                        <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][0]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>">
                    </div>
                    <div class="layout-item__text-container">
                        <div class="layout-item__title" style="color: <?= $textColor ?>;"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?></div>
                    </div>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "7") : ?>
                <div class="layout-item layout-item--type-7" style="background-color: <?= $background ?>;">
                    <div class="layout-item__container--type-7" style="background: url('<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][0]); ?>');background-repeat: no-repeat;background-position: center;background-size: cover;">
                        <div class="layout-item__title--type-7"><?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?></div>
                        <div class="layout-item__container-video--type-7">
                            <? $videoUrl = str_replace("/watch?v=", "/embed/", $layout["PROPERTIES"]["VIDEO_LAYOUT"]["VALUE"]); ?>
                            <iframe width="100%" height="520" src="<?= $videoUrl; ?>" title="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                        <div class="layout-item__empty--type-7" style="color: <?= $textColor ?>;"></div>
                    </div>
                </div>
            <? elseif ($layout["PROPERTIES"]["TYPE_LAYOUT"]["VALUE_XML_ID"] == "8") : ?>
                <div class="layout-item layout-item--type-8" style="background-color: <?= $background ?>;">
                    <div class="layout-item__picture">
                        <img src="<?= CFile::GetPath($layout["PROPERTIES"]["PICTURE_LAYOUT"]["VALUE"][0]); ?>" alt="<?= $layout["PROPERTIES"]["TITLE_LAYOUT"]["VALUE"][0]; ?>">
                    </div>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
<?
}
