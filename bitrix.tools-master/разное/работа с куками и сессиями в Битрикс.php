
<?

function set_cookie($name,$value){
$cookie = new Bitrix\Main\Web\Cookie($name, $value,time()+86400*30);
    $cookie->setSpread(\Bitrix\Main\Web\Cookie::SPREAD_DOMAIN);
    $cookie->setDomain("premiumkorea.ru");
    $cookie->setPath("/");
    $cookie->setSecure(false);
    $cookie->setHttpOnly(false);
Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
}

function get_cookie($name){
$temp_region=Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getCookie($name);
}


$session = \Bitrix\Main\Application::getInstance()->getSession();
$session->set('region', $temp_region);// изменяем
$session->has('region'); // проверяем наличие
$temp_region=$session['region']  // берем значение