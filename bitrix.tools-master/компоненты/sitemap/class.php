<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Sale;



if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once __DIR__ . '/fmap.php';

class FSiteMap extends CBitrixComponent
{
    private $_request;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules(){
        if (!Loader::includeModule('iblock')) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }

        return true;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllMain|CMain
     */
    private function _app()
    {
        global $APPLICATION;
        return $APPLICATION;
    }

    /**
     * Обертка над глобальной переменной
     * @return CAllUser|CUser
     */
    private function _user()
    {
        global $USER;
        return $USER;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {

        return $arParams;
    }

    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам
     */
    public function executeComponent()
    {

        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();

if ($this->StartResultCache()){
        foreach($this->arParams['LINK'] as $link){
            FMap::addLink(key($link),$link[key($link)]);
        }
        foreach($this->arParams['IBLOCK_CATALOG'] as $iblock_id){
            FMap::addIblock(key($iblock_id),$iblock_id[key($iblock_id)]);
        }
        foreach($this->arParams['IBLOCK_TEG'] as $teg){
            FMap::addTegs($teg);
        }

        $this->arResult['CONTENT']=FMap::create();

        $this->endResultCache();
}
       

        if (!empty($this->arParams['AJAX'])) {

            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($this->arResult, JSON_UNESCAPED_UNICODE);
            die;
        }

        $this->includeComponentTemplate();
    }
}
