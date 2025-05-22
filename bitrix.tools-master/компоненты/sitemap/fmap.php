<?

class SimpleTree
{
    public $value;
    public $level;
    public $type = 1;

    public function __construct($level = 0)
    {
        $this->level = $level;
    }

    public function setValue($array_path, $value)
    {
        $this->value = $value;
    }

    public function getValue($array_path)
    {
        return $this->value;
    }

    public function getView()
    {
        ?>
        <li class="item level-<?=$this->level?>"><span><a href="<?=$this->value->url?>"><?=$this->value->code?></a></span></li><?
    }

};

class Tree extends SimpleTree
{
    public $elements;
    public $array_path;
    public $type = 2;
    public function setValue($array_path, $value)
    {
        $this->array_path = $array_path;
        $path =($array_path)?reset($array_path):'';
        array_shift($array_path);
        $array_path = array_values($array_path);
        $path = reset($array_path);

        if (empty($this->elements[$path]) && (count($array_path) > 0)) {
            $this->elements[$path] = new Tree($this->level + 1);
            $this->elements[$path]->setValue($array_path, $value);
            return;
        }

        //если в текущем нет и остаток ==1 имен
        if (empty($this->elements[$path]) && count($array_path) == 0) {
            $this->elements[$path] = new SimpleTree($this->level + 1);
            $this->elements[$path]->setValue($array_path, $value);
            return;
        }
        //если в текущем элемент есть
        if (!empty($this->elements[$path])) {
            $this->elements[$path]->setValue($array_path, $value);
        }
    }

    public function getValue($key)
    {

    }

    public function getView()
    {
        $array_path = $this->array_path;
        $path = array_shift($array_path);
        $elems = [];
        foreach ($this->elements as $item) {
            if ($item->type == 1) {array_unshift($elems, $item);} else { $elems[] = $item;}
        }
        foreach ($elems as $item) {
            ?>
            <ul class="list level-<?=$this->level?>"><?$item->getView()?></ul><?
        }
    }

};

class FMap{

 public static $arrLink;
 public static $arrIblock;
 public static $arrTeg;

 public static function addLink($name,$link){
    self::$arrLink[$name]=$link;        
}   
public static function addIblock($id_iblock,$add_elem=false){
    self::$arrIblock[$id_iblock]=$add_elem;        
}  
public static function addTegs($id_iblock){
    self::$arrTeg[]=$id_iblock;        
}  

public static function create(){
   $struct = new Tree();

   foreach(self::$arrLink as $name=>$link){
    $struct->setValue(explode('/', rtrim($link,'/')), (object)['code' => $name, 'url' => $link]);
}


foreach(self::$arrIblock as $id_iblock=>$add_elem)
{
    $resSect = CIBlockSection::GetList(
        array(),
        array('IBLOCK_ID' => $id_iblock, 'ACTIVE' => 'Y'),
        false,
        array('IBLOCK_ID', 'ID', 'CODE', 'NAME','SECTION_PAGE_URL')
    );

    while ($elem_cod = $resSect->GetNext()) {
        $ipropSectionValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($elem_cod["IBLOCK_ID"], $elem_cod['ID']);
        $res_seo = $ipropSectionValues->getValues();
        $title = ($res_seo["SECTION_PAGE_TITLE"]) ? $res_seo["SECTION_PAGE_TITLE"] : $elem_cod['NAME'];
        $elem = (object) ['code' => $title, 'url' => $elem_cod['SECTION_PAGE_URL']];
        $arr = explode('/', $elem_cod['SECTION_PAGE_URL']);
        if (empty(end($arr))) {
            array_pop($arr);
        }
        $struct->setValue($arr, $elem);
    }

    if($add_elem){
       $elRes = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => $id_iblock, 'ACTIVE' => 'Y'),
        false,
        false,
        array('IBLOCK_ID', 'ID', 'CODE', 'NAME','DETAIL_PAGE_URL')
    );
       while ($elem_cod = $elRes->GetNext()) {
           $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($elem_cod["IBLOCK_ID"], $elem_cod['ID']);
           $res_seo = $ipropElementValues->getValues();
           $title = ($res_seo["ELEMENT_PAGE_TITLE"]) ? $res_seo["ELEMENT_PAGE_TITLE"] : $elem_cod['NAME'];
           $elem = (object) ['code' => $title, 'url' => $elem_cod['DETAIL_PAGE_URL']];
           $arr = explode('/', $elem_cod['DETAIL_PAGE_URL']);
           if (empty(end($arr))) {
            array_pop($arr);
        }
        $struct->setValue($arr, $elem);
    }
}
}

foreach(self::$arrTeg as $id_iblock_teg){
    $elRes = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => $id_iblock_teg, 'ACTIVE' => 'Y'),
        false,
        false,
        array('IBLOCK_ID', 'ID', 'PROPERTY_H1','PROPERTY_LINK')
    );

    while ($elem_cod = $elRes->GetNext()) {
        $elem = (object) ['code' => $elem_cod['PROPERTY_H1_VALUE'], 'url' => $elem_cod['PROPERTY_LINK_VALUE']];
        $arr = explode('/', $elem_cod['PROPERTY_LINK_VALUE']);
        if (empty(end($arr))) {
            array_pop($arr);
        }
        $struct->setValue($arr, $elem);
    }
}

ob_start();

?><div class="sitemap"><?
$struct->getView();
?></div><?
$output = ob_get_clean();

return $output;
}

};