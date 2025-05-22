<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($_POST["fb_type"] == 'fb'){
	$arEventFields = array(
		"NAME" => $_POST["name"],
		"PHONE" => $_POST["phone"],
		"COMMENT" => $_POST["comment"],
	);
	
	// FB это тип почтового события
	// 9 это id почтового шаблона
	// ключи из $arEventFields доступны в тексте почтового шаблона. Вывод так: #NAME#
	if(CEvent::Send("FB", SITE_ID, $arEventFields, "N", 9)){
		echo '<div class="frm_mess">Спасибо за обращение! <br>Скоро мы с Вами свяжемся.</div>';
	}else{
		echo '<div class="frm_mess">Не удалось отправить заявку! Попробуйте позже либо свяжитесь с нами по телефону</div>';
	}
}
?>