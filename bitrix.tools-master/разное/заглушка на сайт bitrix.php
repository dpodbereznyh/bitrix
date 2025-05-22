
<?
AddEventHandler("main", "OnBeforeProlog", "MyOnBeforePrologHandler");
function MyOnBeforePrologHandler()
{
    if(isset($_SERVER['SERVER_NAME'])&&isset($GLOBALS['USER'])){
      if(strpos($_SERVER['SCRIPT_NAME'],'/bitrix/')!==false)return;
   if ($_SERVER['SERVER_NAME']=='funai-com.ru'&&!$GLOBALS['USER']->IsAdmin()){
      //include($_SERVER["DOCUMENT_ROOT"]."/coming-soon/underconstruction.html");
      echo '<div style="display:flex;justify-content:center;align-items:center;height:100vh;font-size:30px">
      <div style="display:flex;flex-direction:column;"><div>Сайт находится в стадии разработки</div>
        <div style="font-size:20px;text-align:right;margin-top:20px;">Разработчик: forumedia.ru</div></div>
      </div>';
      die();
   }
    }

}