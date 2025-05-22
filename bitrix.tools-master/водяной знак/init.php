<?

include "addimg.php";


AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("ImgWater", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("ImgWater", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("main",'OnFileSave',Array("ImgWater", "OnFileSave"));

global $imageWater;
$imageWater = new WSimpleImage();
$imageWater->load($_SERVER["DOCUMENT_ROOT"] . "/upload/watermark_big.png");

class ImgWater
{
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        global $imageWater;
		if (!empty($arFields['PROPERTY_VALUES'][228])||
			!empty($arFields['PROPERTY_VALUES'][229])||
			!empty($arFields['PROPERTY_VALUES'][230])||
			!empty($arFields['PROPERTY_VALUES'][231])||
			!empty($arFields['PROPERTY_VALUES'][232])) {
                if (!empty($arFields['PREVIEW_PICTURE']['tmp_name'])) {
                    $img = new WImgOverlayInit();
                    $img->image_overlay($arFields['PREVIEW_PICTURE']['tmp_name'], $imageWater, $arFields['PREVIEW_PICTURE']['tmp_name']);
                }
                if (!empty($arFields['DETAIL_PICTURE']['tmp_name'])) {
                    $img = new WImgOverlayInit();
                    $img->image_overlay($arFields['DETAIL_PICTURE']['tmp_name'], $imageWater, $arFields['DETAIL_PICTURE']['tmp_name']);
                }

            }

        if ($arFields['IBLOCK_ID'] == 37) {
			if (!empty($arFields['PROPERTY_VALUES'][228])) {
                foreach ($arFields['PROPERTY_VALUES'][173] as $item) {
                    if (!empty($item['VALUE']['tmp_name'])) {
                        $img = new WImgOverlayInit();
                        $img->image_overlay($item['VALUE']['tmp_name'], $imageWater, $item['VALUE']['tmp_name']);
                    }
				}
				}
            }
		if ($arFields['IBLOCK_ID'] == 30) {
			if (!empty($arFields['PROPERTY_VALUES'][230])) {
                foreach ($arFields['PROPERTY_VALUES'][99] as $item) {
                    if (!empty($item['VALUE']['tmp_name'])) {
                        $img = new WImgOverlayInit();
                        $img->image_overlay($item['VALUE']['tmp_name'], $imageWater, $item['VALUE']['tmp_name']);
                    }
				}
				}
            }
		if ($arFields['IBLOCK_ID'] == 26) {
			if (!empty($arFields['PROPERTY_VALUES'][231])) {
                foreach ($arFields['PROPERTY_VALUES'][83] as $item) {
                    if (!empty($item['VALUE']['tmp_name'])) {
                        $img = new WImgOverlayInit();
                        $img->image_overlay($item['VALUE']['tmp_name'], $imageWater, $item['VALUE']['tmp_name']);
                    }
				}
				}
            }
		if ($arFields['IBLOCK_ID'] == 24) {
			if (!empty($arFields['PROPERTY_VALUES'][232])) {
                foreach ($arFields['PROPERTY_VALUES'][73] as $item) {
                    if (!empty($item['VALUE']['tmp_name'])) {
                        $img = new WImgOverlayInit();
                        $img->image_overlay($item['VALUE']['tmp_name'], $imageWater, $item['VALUE']['tmp_name']);
                    }
				}
				}
            }
        }

function OnFileSave(&$arFile, $fileName, $module)
{
 if($module=='medialibrary')
 {
 global $imageWater;
 $arFilePath=$arFile['tmp_name'];
 $img = new WImgOverlayInit();
 $img->image_overlay($arFilePath, $imageWater, $arFilePath);
 }
}
    }
