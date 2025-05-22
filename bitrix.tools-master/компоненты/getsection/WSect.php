<?

require_once $_SERVER["DOCUMENT_ROOT"]
    . "/bitrix/modules/main/include/prolog_before.php";

Bitrix\Main\Loader::includeModule("iblock");

class WSect
{

    public static $arResult = [];

    public static function addLinks($IBLOCK_ID, &$ELEMENT, $type = false,$component=false)
    {
        $app = (!$component)?(new CBitrixComponent()):$component;
        foreach ($ELEMENT as &$elems) {
            $elem      = $elems['ID'];
            $arButtons = CIBlock::GetPanelButtons(
                $IBLOCK_ID,
                !($type) ? $elem : 0,
                ($type) ? $elem : 0,
                array("SESSID" => false)
            );
            $elems["ADD_LINK"]  = (!$type) ? $arButtons["edit"]["add_element"]["ACTION_URL"] : $arButtons["edit"]["add_section"]["ACTION_URL"];
            $elems["EDIT_LINK"] = (!$type) ? $arButtons["edit"]["edit_element"]["ACTION_URL"] : $arButtons["edit"]["edit_section"]["ACTION_URL"];
            $elems["DELETE_LINK"]= (!$type)? $arButtons["edit"]["delete_element"]["ACTION_URL"]:$arButtons["edit"]["delete_section"]["ACTION_URL"];
            $elems["ADD_LINK_TEXT"]= (!$type)? $arButtons["edit"]["add_element"]["TEXT"]:$arButtons["edit"]["add_section"]["TEXT"];
            $elems["EDIT_LINK_TEXT"]= (!$type)? $arButtons["edit"]["edit_element"]["TEXT"]:$arButtons["edit"]["edit_section"]["TEXT"];
            $elems["DELETE_LINK_TEXT"]= (!$type)? $arButtons["edit"]["delete_element"]["TEXT"]:$arButtons["edit"]["delete_section"]["TEXT"];
            $app->AddEditAction($elem, $elems['ADD_LINK'],$elems["ADD_LINK_TEXT"]);
            $app->AddEditAction($elem, $elems['EDIT_LINK'],$elems["EDIT_LINK_TEXT"]);
            $app->AddDeleteAction($elem, $elems['DELETE_LINK'],$elems["DELETE_LINK_TEXT"],array("CONFIRM" => 'Точно удалить?'));
            $elems["AREA_ID"] = $app->GetEditAreaID($elem);
        }
    }

     public static function recSect(&$arRes,&$res_list)
     {
        foreach($res_list as $key=>$item){
        if($item['IBLOCK_SECTION_ID']==$arRes['ID']){
            $arRes['IS_PARENT']="Y";
            unset($res_list[$key]);
            self::recSect($item,$res_list);
            $arRes['ITEMS'][]=$item;
        }}
     }

    public static function getResult(
        $IBLOCK_ID,
        $FILTER,
        $SORT = array('sort' => 'ASC', 'id' => 'DESC'),
        $NAV = false,
        $SELECT = [],
        $group=false
    ) {
        $filter = ['IBLOCK_ID' => $IBLOCK_ID];
        $filter = array_merge($filter, $FILTER);
        $res    = CIBlockSection::GetList($SORT, $filter, false, $SELECT, $NAV);
        $arRes  = [];
        $temp=[];
        
        while ($ob = $res->GetNext()) {
            $temp[]            = $ob;
        }
        
        if($group){
        $add=1;
        $arRes=[0=>array('ID'=>false)];
        self::recSect($arRes[0],$temp);
        return $arRes;
        }
                else return $temp; 
    }

}
