if (\Bitrix\Main\Loader::includeModule('statistic'))
{
   $res = \CUserOnline::getList($guests, $sessions);
   echo 'Всего ' . $guests . ' гостей, и ' .$sessions . ' сессий';
   echo '
';
   while ($row = $res->fetch())
   {
      echo 'Гость #' . $row['ID'] . 
          ', из ' . $row['REGION_NAME'] . 
          ', его IP = ' . $row['IP_LAST'] . 
         ', последний хит был на ' . $row['URL_LAST'];
      echo '
';
   }
}