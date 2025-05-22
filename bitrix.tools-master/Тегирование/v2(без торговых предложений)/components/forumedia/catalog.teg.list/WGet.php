<?

require_once $_SERVER["DOCUMENT_ROOT"]
. "/bitrix/modules/main/include/prolog_before.php";

Bitrix\Main\Loader::includeModule("iblock");

class WGet
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

    public static function getResult(
        $IBLOCK_ID,
        $FILTER,
        $SORT = array('sort' => 'ASC', 'id' => 'DESC'),
        $NAV = false,
        $SELECT = []) 
    {
        $filter = ['IBLOCK_ID' => $IBLOCK_ID];
        $filter = array_merge($filter, $FILTER);
        $res    = CIBlockElement::GetList($SORT, $filter, false, $NAV, $SELECT);
        $arRes  = [];
        while ($ob = $res->GetNextElement()) {
            $temp               = $ob->GetFields();
            $temp['PROPERTIES'] = $ob->GetProperties();
            $arRes[]            = $temp;
        }
        return $arRes;
    }

    public static function getTegs($items,$CATALOG_IBLOCK_ID,$CATALOG_SECTION_ID,$counts=false,$brand=false){
      $tegs=[];

      foreach($items as $item)
      {
        if(empty($item['PROPERTIES']['PATTERN']['VALUE']))
        {
            $tegs[]=$item;
        }

        if(!empty($item['PROPERTIES']['PATTERN']['VALUE']))
        {
            foreach($item['PROPERTIES']['PATTERN']['VALUE'] as $prop)
            {
              $filter=['IBLOCK_ID'=>$CATALOG_IBLOCK_ID,'SECTION_ID'=>$CATALOG_SECTION_ID];
              if($prop!='BRAND'&&$brand)
                {$filter['PROPERTY_BRAND']=$brand;}
            $val_res    = CIBlockElement::GetList(
                [], 
                $filter, 
                ['PROPERTY_'.$prop], 
                false,['IBLOCK_ID','PROPERTY_'.$prop]
            ); 
            $ids=[];
            while($ob = $val_res->Fetch()) 
            {
                $ids[]=$ob['PROPERTY_'.$prop.'_VALUE'];
            }

            if($prop=='BRAND'&&!empty($ids)){
             $res    = CIBlockElement::GetList(
                ['sort'=>'asc','name'=>'desc'], 
                ['ID'=>$ids], 
                false,false,
                ['IBLOCK_ID','CODE','NAME','ID']
            );
             $list_prop[$prop]=[];
                 //var_dump($brand);
             while($ob = $res->Fetch()) 
             {  
                if($brand&&$prop=='BRAND'&&$ob['ID']==$brand){$list_prop[$prop][]=$ob;}
                if($brand&&$prop!='BRAND'){$list_prop[$prop][]=$ob;}
                if(!$brand){
                    $list_prop[$prop][]=$ob;
                }
            }
        } 
        if($brand&&$prop!='BRAND'&&!empty($ids)){
            $params_tr = array("replace_space"=>"_","replace_other"=>"_");

            foreach($ids as $ob)
            {
               if(!empty($ob)){$list_prop[$prop][]=['NAME'=>$ob,'CODE'=>Cutil::translit($ob,"ru",$params_tr)];} 
           }

       }
   }

   $arrLinks=[];

   
       $bdir=$GLOBALS['APPLICATION']->GetCurPage();
       $first_teg=false;
       $arFieldsTeg = [];
       $mdir=explode("/",trim($bdir,"/"));
       $arr_path=[];
       $mdirtemp=$mdir;
       $last_dirs=[];
       $last_sect=$mdir[count($mdir)-1];
       $pred_last_sect=isset($mdir[count($mdir)-2])?$mdir[count($mdir)-2]:false;
                //определяем список свойств для шаблона, последнюю секцию, последний тег идущий перед шаблоном (если он был)
       $obj_last_dir=[];
       for($i=count($mdir)-1;$i>=0;--$i)
       {  
        if($mdir[$i]=='catalog')break;
        $resdir=CIBlockSection::GetList([],
            ['IBLOCK_ID'=>$CATALOG_IBLOCK_ID,
            'CODE'=>$mdir[$i]],
            false,
            ['IBLOCK_ID','ID','SECTION_PAGE_URL','NAME']);

        $cpage1 = '/' . implode('/',$mdirtemp) ;
        $cpage2 = $cpage1. '/';
        array_pop($mdirtemp);

        $teg_simple=false;

        $flg_fnd=false;
        while($obj = $resdir->GetNext())
        {
            if(trim($obj['SECTION_PAGE_URL'],'/')==trim($cpage1,'/'))
            {
                $obj_last_dir=$obj;$flg_fnd=true;
            }
        }

        if($flg_fnd){break;} 
    }

if(!$brand){
   // var_dump($pred_last_sect);
    foreach($list_prop['BRAND'] as $prop0){
        $code=str_replace("-","_",mb_strtolower($prop0['CODE']));
        if($code==$last_sect||$code==$pred_last_sect){
            return [];
        }
    }
    $obj_last_dir['~SECTION_PAGE_URL']=$GLOBALS['APPLICATION']->GetCurPage();
}


foreach($list_prop['BRAND'] as $prop0)
    {       $tegTemp=$item;
        $code='BRAND';
        $code_val=str_replace("-","_",mb_strtolower($prop0['CODE']));
                //if(strpos($GLOBALS['APPLICATION']->GetCurDir(),'/'.$code_val.'/')!==false)continue;
        $tegTemp=$item;
        $tegTemp['IS_SHABLON']='Y';
        $tegTemp['PROPERTIES']['FILTER_URL']['VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp['PROPERTIES']['FILTER_URL']['VALUE']);
        $tegTemp['PROPERTIES']['FILTER_URL']['~VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp['PROPERTIES']['FILTER_URL']['~VALUE']);
        $tegTemp['PROPERTIES']['NAME']['VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp['PROPERTIES']['NAME']['VALUE']);
        $tegTemp['PROPERTIES']['NAME']['~VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp['PROPERTIES']['NAME']['~VALUE']);

        if($tegTemp['PROPERTIES']['LINK']['VALUE'][0]!="/")
        {
            $curDir=$obj_last_dir['~SECTION_PAGE_URL'];

            if(($pos=strpos($curDir,'/filter/'))===false){
                $tegTemp['PROPERTIES']['LINK']['VALUE']=$obj_last_dir['~SECTION_PAGE_URL'].str_replace('#'.$code.'#',$code_val,trim($tegTemp['PROPERTIES']['LINK']['VALUE'],'/')).'/';
                $tegTemp['PROPERTIES']['LINK']['~VALUE']=$obj_last_dir['~SECTION_PAGE_URL'].str_replace('#'.$code.'#',$code_val,trim($tegTemp['PROPERTIES']['LINK']['~VALUE'],'/')).'/';
            }else{
                $tmp_code=str_replace('#'.$code.'#',$code_val,trim($tegTemp['PROPERTIES']['LINK']['VALUE'],'/'));
                $tegTemp['PROPERTIES']['LINK']['VALUE']=str_replace('/filter/','/'.$tmp_code.'/filter/',$curDir);
                $tegTemp['PROPERTIES']['LINK']['~VALUE']=str_replace('/filter/','/'.$tmp_code.'/filter/',$curDir);
            }
        }
        else
        {
            $tegTemp['PROPERTIES']['LINK']['VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp['PROPERTIES']['LINK']['VALUE']);
            $tegTemp['PROPERTIES']['LINK']['~VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp['PROPERTIES']['LINK']['~VALUE']);
        }

        $tegTemp['PROPERTIES']['NAME']['VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['NAME']['VALUE']);
        $tegTemp['PROPERTIES']['NAME']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['NAME']['~VALUE']);
        $tegTemp['PROPERTIES']['H1']['VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['H1']['VALUE']);
        $tegTemp['PROPERTIES']['H1']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['H1']['~VALUE']);
        $tegTemp['PROPERTIES']['TITLE']['VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['TITLE']['VALUE']);
        $tegTemp['PROPERTIES']['TITLE']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['TITLE']['~VALUE']);
        $tegTemp['PROPERTIES']['DESCRIPTION']['VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['DESCRIPTION']['VALUE']);
        $tegTemp['PROPERTIES']['DESCRIPTION']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['DESCRIPTION']['~VALUE']);
        $tegTemp['PROPERTIES']['TEXT_BOTTOM']['VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['TEXT_BOTTOM']['VALUE']);
        $tegTemp['PROPERTIES']['TEXT_BOTTOM']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop0['NAME'],$tegTemp['PROPERTIES']['TEXT_BOTTOM']['~VALUE']);


        foreach($list_prop['SERIAL'] as $prop)
            {    $tegTemp1=$tegTemp;
                $code='SERIAL';
                $code_val=str_replace("-","_",mb_strtolower($prop['CODE']));
                if(strpos($obj_last_dir['~SECTION_PAGE_URL'],'/'.$code_val.'/')!==false)continue;

                $tegTemp1['IS_SHABLON']='Y';
                $tegTemp1['PROPERTIES']['FILTER_URL']['VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp1['PROPERTIES']['FILTER_URL']['VALUE']);
                $tegTemp1['PROPERTIES']['FILTER_URL']['~VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp1['PROPERTIES']['FILTER_URL']['~VALUE']);
                $tegTemp1['PROPERTIES']['NAME']['VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp1['PROPERTIES']['NAME']['VALUE']);
                $tegTemp1['PROPERTIES']['NAME']['~VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp1['PROPERTIES']['NAME']['~VALUE']);
                

                if($tegTemp1['PROPERTIES']['LINK']['VALUE'][0]!="/")
                {
                    $curDir=$obj_last_dir['~SECTION_PAGE_URL'];

                    if(($pos=strpos($curDir,'/filter/'))===false){
                        $tegTemp1['PROPERTIES']['LINK']['VALUE']=$obj_last_dir['~SECTION_PAGE_URL'].str_replace('#'.$code.'#',$code_val,trim($tegTemp1['PROPERTIES']['LINK']['VALUE'],'/')).'/';
                        $tegTemp1['PROPERTIES']['LINK']['~VALUE']=$obj_last_dir['~SECTION_PAGE_URL'].str_replace('#'.$code.'#',$code_val,trim($tegTemp1['PROPERTIES']['LINK']['~VALUE'],'/')).'/';
                    }else{

                        $tmp_code=str_replace('#'.$code.'#',$code_val,trim($tegTemp1['PROPERTIES']['LINK']['VALUE'],'/'));

                        $tegTemp1['PROPERTIES']['LINK']['VALUE']=str_replace('/filter/','/'.$tmp_code.'/filter/',$curDir);
                        $tegTemp1['PROPERTIES']['LINK']['~VALUE']=str_replace('/filter/','/'.$tmp_code.'/filter/',$curDir);
                    }
                }
                else
                {
                    $tegTemp1['PROPERTIES']['LINK']['VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp1['PROPERTIES']['LINK']['VALUE']);
                    $tegTemp1['PROPERTIES']['LINK']['~VALUE']=str_replace('#'.$code.'#',$code_val,$tegTemp1['PROPERTIES']['LINK']['~VALUE']);
                }

                $tegTemp1['PROPERTIES']['NAME']['VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['NAME']['VALUE']);
                $tegTemp1['PROPERTIES']['NAME']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['NAME']['~VALUE']);
                $tegTemp1['PROPERTIES']['H1']['VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['H1']['VALUE']);
                $tegTemp1['PROPERTIES']['H1']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['H1']['~VALUE']);
                $tegTemp1['PROPERTIES']['TITLE']['VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['TITLE']['VALUE']);
                $tegTemp1['PROPERTIES']['TITLE']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['TITLE']['~VALUE']);
                $tegTemp1['PROPERTIES']['DESCRIPTION']['VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['DESCRIPTION']['VALUE']);
                $tegTemp1['PROPERTIES']['DESCRIPTION']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['DESCRIPTION']['~VALUE']);
                $tegTemp1['PROPERTIES']['TEXT_BOTTOM']['VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['TEXT_BOTTOM']['VALUE']);
                $tegTemp1['PROPERTIES']['TEXT_BOTTOM']['~VALUE']=str_replace('#'.$code.'_NAME#',$prop['NAME'],$tegTemp1['PROPERTIES']['TEXT_BOTTOM']['~VALUE']);

                $tegs[]=$tegTemp1;
                if(!empty($counts) && count($tegs)>=$counts){break;}
            }  

            if(!$brand)$tegs[]=$tegTemp;
            if(!empty($counts) && count($tegs)>=$counts){break;}
        }
        if(!empty($counts) && count($tegs)>=$counts){break;}
    }

}
return $tegs;
}

}
