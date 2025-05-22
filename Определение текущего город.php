if (\Bitrix\Main\Loader::includeModule('statistic'))
{
   $res = \CGuest::getList(
      $by = 's_last_date',
      $order = 'desc',
      [
         'SESS_GUEST_ID' => $_SESSION['SESS_GUEST_ID']
      ],
      $isfiltered
   );
   if ($row = $res->fetch())
   {
      echo 'Ваш город: ' . $row['LAST_REGION_NAME'];
   }
}
//>> Ваш город: Москва