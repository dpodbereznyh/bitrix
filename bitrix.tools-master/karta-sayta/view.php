<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Карта сайта");
$APPLICATION->SetPageProperty("description", "Карта сайта");
$APPLICATION->SetTitle("Карта сайта");
CModule::IncludeModule("iblock");
?>
<style>
    ul.list{
    margin:auto;
    padding:0 20px;
    }
</style>
<?

class SimpleTree
{
    public $value;
    public $level;
    public $type=1;

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
    <li class="item level-<?= $this->level ?>"><span><a href="<?=$this->value->url?>"><?= $this->value->code ?></a></span></li><?
    }

}

;

class Tree extends SimpleTree
{
    public $elements;
    public $array_path;
    public $type=2;
    public function setValue($array_path, $value)
    {
        $this->array_path = $array_path;
        $path = reset($array_path);
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
        $elems=[];
        foreach ($this->elements as $item) {
            if($item->type==1){array_unshift($elems,$item);}else{$elems[]=$item;}
        }
        foreach ($elems as $item) {
            ?>
            <ul class="list level-<?= $this->level ?>"><? $item->getView() ?></ul><?
        }
    }

}

;

if (file_exists('map.xml')) {
    $xml = simplexml_load_file('../sitemap.xml');
    $struct = new Tree();
    $arrTitle=[
        'lkmtorg.ru'=>'Главная',
        'lkmtorg.forumedia.ru'=>'Главная',
        'catalog'=>'Каталог'];
    $res = CIBlockElement::GetList(
        array(),
        array('IBLOCK_ID' => array(17),'ACTIVE'=>'Y'),
        false,
        false,
        array('ID','CODE','NAME')
    );
    $resSect = CIBlockSection::GetList(
        array(),
        array('IBLOCK_ID' => array(17),'ACTIVE'=>'Y'),
        false,
        array('ID','CODE','NAME')
    );
    while($elem_cod=$res->Fetch()){
        $arrTitle[$elem_cod['CODE']]=$elem_cod['NAME'];
    }
    while($elem_cod=$resSect->Fetch()){
        $arrTitle[$elem_cod['CODE']]=$elem_cod['NAME'];
    }
    foreach ($xml->url as $data) {
        $arr = explode('/', (string)$data->loc);
        unset($arr[0]);
        unset($arr[1]);
        if (end($arr)[0]=='?'){continue;}
        if (empty(end($arr))) {
            array_pop($arr);
        }
        $code=(!empty($arrTitle[end($arr)]))?($arrTitle[end($arr)]):end($arr);
        if (empty($arrTitle[end($arr)])){continue;}
        $elem=(object)['code'=>$code,'url'=>(string)$data->loc];
        $struct->setValue($arr, $elem);

    }
    $struct->getView();
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>