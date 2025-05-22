<?

require_once($_SERVER["DOCUMENT_ROOT"]
    . "/bitrix/modules/main/include/prolog_before.php");

Bitrix\Main\Loader::includeModule("iblock");

class WMessages
{

    public static $arResult = [];

    public static function addLinks($IBLOCK_ID,&$ELEMENT_ID)
    {
        $app = new CBitrixComponent();
        foreach ($ELEMENT_ID as &$elems) 
        {
            $elem=$elems['ID'];
            $arButtons = CIBlock::GetPanelButtons(
                $IBLOCK_ID,
                $elem,
                0,
                array("SECTION_BUTTONS" => false, "SESSID" => false)
            );
            $elems["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
            $elems["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
            $elems["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
            $elems["ADD_LINK_TEXT"] = $arButtons["edit"]["add_element"]["TEXT"];
            $elems["EDIT_LINK_TEXT"] = $arButtons["edit"]["edit_element"]["TEXT"];
            $elems["DELETE_LINK_TEXT"] = $arButtons["edit"]["delete_element"]["TEXT"];
            $app->AddEditAction($elem, $elems['ADD_LINK'], $elems["ADD_LINK_TEXT"]);
            $app->AddEditAction($elem, $elems['EDIT_LINK'], $elems["EDIT_LINK_TEXT"]);
            $app->AddDeleteAction($elem, $elems['DELETE_LINK'], $elems["DELETE_LINK_TEXT"], array("CONFIRM" => 'Точно удалить?'));
            $elems["AREA_ID"] = $app->GetEditAreaID($elem);
        }
    }

    public static function getResult(
        $IBLOCK_ID,
        $ELEMENT_CATALOG_ID,
        $SORT = array("SORT" => "ASC"),
        $SELECT = [],
        $PAGES = false
    ) {
        $res   = CIBlockElement::GetList($SORT,
            ['IBLOCK_ID' => $IBLOCK_ID,
             'PROPERTY_CATALOG' => $ELEMENT_CATALOG_ID,
             'ACTIVE'=>'Y'],
            false, $PAGES, $SELECT);

        $arRes = [];
        while ($ob = $res->GetNext()) {
            if ( ! isset($list[$ob['ID']])) {
                $arRes[]         = $ob;
                $list[$ob['ID']] = count($arRes) - 1;
            } else {
                $val=is_array($arRes[$list[$ob['ID']]]['PROPERTY_FILES_VALUE'])?
                    $arRes[$list[$ob['ID']]]['PROPERTY_FILES_VALUE']:
                    array($arRes[$list[$ob['ID']]]['PROPERTY_FILES_VALUE']);
                $val[]=$ob['PROPERTY_FILES_VALUE'];
                $arRes[$list[$ob['ID']]]['PROPERTY_FILES_VALUE'] = $val;
            }
        }
        return $arRes;
    }

    public static function update($ELEMENT_ID, $IBLOCK_ID, $PROPERTIES)
    {
        foreach ($PROPERTIES as $prop) {
            CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, $IBLOCK_ID,
                array($prop['CODE'] => $prop['VALUE']));
        }
    }

}
