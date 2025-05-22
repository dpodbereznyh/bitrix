
///Подключение стилей и скриптов с D7:

use Bitrix\Main\Page\Asset;

// Для подключения css
Asset::getInstance()->addCss("/bitrix/css/main/bootstrap.min.css");

// Для подключения скриптов
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/myscripts.js");

// Подключение мета тегов или сторонних файлов
Asset::getInstance()->addString("<link rel='shortcut icon' href='/local/images/favicon.ico' />");

////////////////////////////////////////////////////////////////////////////////////

////Подключение стилей и js в шаблонах компонентов
$this->addExternalCss("/local/styles.css");
$this->addExternalJS("/local/liba.js");

$('document').ready(function () {
	$('.block-filter-arrow').on('click', function () {
		$(this).toggleClass('open')
		if($(this).hasClass('open')) {
			$('.block-filter.bx-filter').fadeIn();
		} else {
			$('.block-filter.bx-filter').fadeOut();
		}
	})
})