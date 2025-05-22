<?

if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


foreach ($arResult["ITEMS"] as &$item) {
    $image['src']    = '/bitrix/components/bitrix/sale.basket.basket/templates/.default/images/no_photo.png';
    $image['height'] = '100';
    $image['width']  = '100';
    if (empty($item['PRODUCT']['FIELDS']['PREVIEW_PICTURE']) && !empty($item['PRODUCT']['FIELDS']['DETAIL_PICTURE'])) {
        $image = CFile::ResizeImageGet($item['PRODUCT']['FIELDS']['DETAIL_PICTURE'], array('width' => 100, 'height' => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    } elseif (!empty($item['PRODUCT']['FIELDS']['PREVIEW_PICTURE'])) {
        $image = CFile::ResizeImageGet($item['PRODUCT']['FIELDS']['PREVIEW_PICTURE'], array('width' => 100, 'height' => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true);
    }

    $item['PREVIEW_PICTURE']['SRC']    = $image['src'];
    $item['PREVIEW_PICTURE']['HEIGHT'] = $image['height'];
    $item['PREVIEW_PICTURE']['WIDTH']  = $image['width'];
}

