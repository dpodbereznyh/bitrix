<?CJSCore::Init(['popup']);?>
<script>
var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);

		

	var createWindow=function(){
		var BasketButton = function(params)
		{
			BasketButton.superclass.constructor.apply(this, arguments);
			this.buttonNode = BX.create('SPAN', {
				props: {className: 'btn btn-primary btn-buy btn-sm', id: this.id},
				style: typeof params.style === 'object' ? params.style : {},
				text: params.text,
				events: this.contextEvents
			});

			if (BX.browser.IsIE())
			{
				this.buttonNode.setAttribute('hideFocus', 'hidefocus');
			}
		};

		BX.extend(BasketButton, BX.PopupWindowButton);

		<?=$obName?>.initPopupWindow();
		productPict = <?=$obName?>.product.pict.SRC;

		let popupContent = '<div style="width: 100%; margin: 0; text-align: center;">'
		+ '<img src="' + productPict + '" height="130" style="max-height:130px"></div>';


		popupButtons = [new BasketButton({
			text: BX.message('BTN_MESSAGE_BASKET_REDIRECT'),
			events: {
				click: BX.delegate(<?=$obName?>.basketRedirect, <?=$obName?>)
			}
		})];

		<?=$obName?>.obPopupWin.setTitleBar(BX.message('TITLE_SUCCESSFUL'));
		<?=$obName?>.obPopupWin.setContent(popupContent);
		<?=$obName?>.obPopupWin.setButtons(popupButtons);
		<?=$obName?>.obPopupWin.show();
	}

	var windowMess=function(message,btnShow=true,messTitle='Zodiac')
{
 BX.ready(function () {
        var popup = new BX.PopupWindow("popup-message-basket", null, {
            content: '<span style="font-size:16px">'+message+'</span>',
            width: 300, // ширина окна
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
            min_width: 300, // минимальная ширина окна
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

        popup.show();
    });
    
}
</script>

многоразовая форма после аякса;
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

        