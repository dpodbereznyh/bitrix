<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ExampleCompSimple extends CBitrixComponent {
    public  $arElements = array();
    public $presentPost = 'Педиатр';
    public $presentPostXmlId = 'pediatr';
    protected function getElements() {
        $arElements = CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => 2, 'ACTIVE' => 'Y', 'PROPERTY_PRESENT_POST' => $this -> $presentPost
            ),
            false,
            array(),
            array(
                'ID',
                'NAME',
                'DETAIL_PAGE_URL',
                'PREVIEW_PICTURE',
                'PROPERTY_RATING',
                'PROPERTY_EXPERIENCE',
                'PROPERTY_PRESENT_POST',
                'PROPERTY_DIGNITY',
                'PROPERTY_SERVICES',
                'PROPERTY_GENDER',
                'PROPERTY_CLINICS',
            )
        );
    }
    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        // тут пишем логику обработки параметров, дополнение параметрами по умолчанию
        // и прочие нужные вещи
        return $arParams;
    }
    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent() {

        // что-то делаем и результаты работы помещаем в arResult, для передачи в шаблон
        $this->arResult['TEST'] = 'some result data for template';
        $this->includeComponentTemplate();
    }

}
