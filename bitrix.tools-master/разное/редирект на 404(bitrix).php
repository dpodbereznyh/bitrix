<?if(!$USER->IsAdmin()){
	\CHTTP::setStatus("404 Not Found");

if ($APPLICATION->RestartWorkarea())
{
	require(\Bitrix\Main\Application::getDocumentRoot() . "/404.php");
	die();
}
}?>
//---------------------------------v2---------------------------------------
<?
	\Bitrix\Iblock\Component\Tools::process404(
       'Не найден', //Сообщение
       true, // Нужно ли определять 404-ю константу
       true, // Устанавливать ли статус
       true, // Показывать ли 404-ю страницу
       false // Ссылка на отличную от стандартной 404-ю
	);?>
