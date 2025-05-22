<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?/*<script>(function(t, p) {window.Marquiz ? Marquiz.add([t, p]) : document.addEventListener('marquizLoaded', function() {Marquiz.add([t, p])})})('Pop', {id: '641082f026fb37003d51c405', title: 'Для Вас', text: 'Заголовок квиза', delay: 1, textColor: '#ffffff', bgColor: '#00bcbf', svgColor: '#ffffff', closeColor: '#ffffff', bonusCount: 0, type: 'full', position: 'position_top', blicked: true})</script>
<!-- Marquiz script start -->
<script>
(function(w, d, s, o){
  var j = d.createElement(s); j.async = true; j.src = '//script.marquiz.ru/v2.js';j.onload = function() {
    if (document.readyState !== 'loading') Marquiz.init(o);
    else document.addEventListener("DOMContentLoaded", function() {
      Marquiz.init(o);
    });
  };
  d.head.insertBefore(j, d.head.firstElementChild);
})(window, document, 'script', {
    host: '//quiz.marquiz.ru',
    region: 'eu',
    id: '641082f026fb37003d51c405',
    autoOpen: false,
    autoOpenFreq: 'once',
    openOnExit: true,
    disableOnMobile: true
  }
);
</script>

<div data-marquiz-id="641082f026fb37003d51c405"></div>

<script>(function(t, p) {window.Marquiz ? Marquiz.add([t, p]) : document.addEventListener('marquizLoaded', function() {Marquiz.add([t, p])})})('Inline', {id: '641082f026fb37003d51c405', buttonText: 'Пройти тест', bgColor: '#00bcbf', textColor: '#ffffff', rounded: true, shadow: 'rgba(0, 188, 191, 0.5)', blicked: true, buttonOnMobile: true, width: '1920', height: '500'})</script>

<script>(function(t, p) {window.Marquiz ? Marquiz.add([t, p]) : document.addEventListener('marquizLoaded', function() {Marquiz.add([t, p])})})('Pop', {id: '641082f026fb37003d51c405', title: 'Для Вас', text: 'Заголовок квиза', delay: 1, textColor: '#ffffff', bgColor: '#00bcbf', svgColor: '#ffffff', closeColor: '#ffffff', bonusCount: 0, type: 'full', position: 'position_top', blicked: true})</script>

<script>(function(t, p) {window.Marquiz ? Marquiz.add([t, p]) : document.addEventListener('marquizLoaded', function() {Marquiz.add([t, p])})})('Widget', {id: '641082f026fb37003d51c405', position: 'left', delay: 3})</script>
*/?>

<?

$res = \Bitrix\Sale\Location\LocationTable::getList(array(
      'filter' => array('=NAME.NAME'=>$GLOBALS['REGIONS']['ACTIVE'][0]['NAME'],'=NAME.LANGUAGE_ID' => 'ru', 'TYPE_CODE' => 'CITY'),
      'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
    ));
    $item_reg = $res->fetch();

?><div class="item_buttons_counter_block">
  <input type="text" value="1">
</div>
         
<span class="calc-delivery" data-id="10876" data-region=<?=$item_reg['CODE']?>>
<svg class="main-calculate-delivery__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="0 0 18 14">
                                        <path id="Ellipse_299_copy_2" data-name="Ellipse 299 copy 2" class="cls-1" d="M1346,916a3,3,0,0,1-6,0h-1a1.012,1.012,0,0,1-.42-0.1,1.917,1.917,0,0,1-.58.1h-2a3,3,0,0,1-6,0,2.344,2.344,0,0,1,.03-0.264A1.992,1.992,0,0,1,1329,914v-7a2,2,0,0,1,2-2h7a2,2,0,0,1,2,2h3l4,4v4A1,1,0,0,1,1346,916Zm-13,1a1,1,0,1,0-1-1A1,1,0,0,0,1333,917Zm5-10h-7v6.778a2.961,2.961,0,0,1,4.22.221H1338v-7Zm5,10a1,1,0,1,0-1-1A1,1,0,0,0,1343,917Zm2-5.313L1342.31,909H1340v5h0.78a2.96,2.96,0,0,1,4.22-.222v-2.09Z" transform="translate(-1329 -905)"></path>
                                    </svg>

          Расчитать доставку</span>

          <script>

var popup;

var windowMess=function(message,btnShow=true,messTitle='')
{
 BX.ready(function () {
            popup = new BX.PopupWindow("popup-message-basket", null, {
            content: '<span class="body-content">'+message+'</span>',
            width: 320, // ширина окна
            height: 220, // высота окна
            zIndex: 100, // z-index
            closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 1
            },
            titleBar: messTitle,
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: false, // можно двигать или нет
            resizable: false, // можно ресайзить
            min_height: 100, // минимальная высота окна
            min_width: 320, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
           // angle: true, // появится уголок
            overlay: {
                // объект со стилями фона
                backgroundColor: 'black',
                opacity: 500
            }, 
            buttons: [
      (btnShow)?new BX.PopupWindowButton({
          text: "Перейти в корзину",
          className: "popup-form-button",
          events: {click: function(){
           location.href="/basket/";
          }}
      }):''],
            events: {
               onPopupShow: function() {
                  // Событие при показе окна
               },
               onPopupClose: function() {
                  // Событие при закрытии окна                
               }
            }
        });
    });
    
}

            $('.calc-delivery').on('click',function(){
              $.post('/ajax/get_delivery.php',[
                {name:'count',value:$('.item_buttons_counter_block input').val()},
                {name:'id',value:$(this).data('id')},
                {name:'region',value:$(this).data('region')}]).done(function(result){
                if(popup!=undefined){
                  $('.body-content').html(result);
                  popup.show();}
                  else{
                  windowMess('',false,'Расчет доставки');
                  $('.body-content').html(result);
                  popup.show();}
              })
            });
          </script>
  

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>