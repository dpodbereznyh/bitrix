<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Compare");

//фильтр по элементам сравнения из кук
if(isset($_COOKIE['compare_list']) && !empty($_COOKIE['compare_list'])){
	$compare_list = explode(',', $_COOKIE['compare_list']);

	$GLOBALS['arrFilter'] = array('ID' => $compare_list);
}
if(!empty($GLOBALS['arrFilter']['ID'])){
	?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "compare", Array(
	"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
		"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
		"BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
		"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"COMPATIBLE_MODE" => "N",	// Включить режим совместимости
		"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
		"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
		"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
		"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
		"FILTER_NAME" => "arrFilter",	// Имя массива со значениями фильтра для фильтрации элементов
		"IBLOCK_ID" => "2",	// Инфоблок
		"IBLOCK_TYPE" => "products",	// Тип инфоблока
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
		"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
		"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
		"PAGER_TITLE" => "Товары",	// Название категорий
		"PAGE_ELEMENT_COUNT" => "18",	// Количество элементов на странице
		"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
		"PRICE_CODE" => "",	// Тип цены
		"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
		"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
		"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",	// Название переменной, в которой передается количество товара
		"SECTION_CODE" => "",	// Код раздела
		"SECTION_ID" => "0",	// ID раздела
		"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
		"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
		"SECTION_USER_FIELDS" => array(	// Свойства раздела
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",	// Включить поддержку ЧПУ
		"SET_BROWSER_TITLE" => "Y",	// Устанавливать заголовок окна браузера
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"SET_META_DESCRIPTION" => "Y",	// Устанавливать описание страницы
		"SET_META_KEYWORDS" => "Y",	// Устанавливать ключевые слова страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"SHOW_404" => "N",	// Показ специальной страницы
		"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
		"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
		"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
		"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
		"SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
		"COMPONENT_TEMPLATE" => "products",
		"PROPERTY_CODE_MOBILE" => "",	// Свойства товаров, отображаемые на мобильных устройствах
		"TEMPLATE_THEME" => "blue",	// Цветовая тема
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",	// Вариант отображения товаров
		"ENLARGE_PRODUCT" => "STRICT",	// Выделять товары в списке
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",	// Порядок отображения блоков товара
		"SHOW_SLIDER" => "Y",	// Показывать слайдер для товаров
		"ADD_PICT_PROP" => "-",	// Дополнительная картинка основного товара
		"LABEL_PROP" => "",	// Свойства меток товара
		"MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
		"MESS_BTN_SUBSCRIBE" => "Подписаться",	// Текст кнопки "Уведомить о поступлении"
		"MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
		"MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
		"MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",	// Сообщение о недоступности услуги
		"RCM_TYPE" => "personal",	// Тип рекомендации
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],	// Параметр ID продукта (для товарных рекомендаций)
		"SHOW_FROM_SECTION" => "N",	// Показывать товары из раздела
		"USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
		"LAZY_LOAD" => "N",	// Показать кнопку ленивой загрузки Lazy Load
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",	// Текст кнопки "Показать ещё"
		"LOAD_ON_SCROLL" => "N",	// Подгружать товары при прокрутке до конца
	),
	false
);?>
	<div class="clean_compare" style=" border: 1px solid; font-size: 20px; text-align: center; padding: 20px; margin: 20px; cursor: pointer; ">Очистить</div>
	<?
}else{
	?>
	Нет элементов для сравнения
	<?
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>