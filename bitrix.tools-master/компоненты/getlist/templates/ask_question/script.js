$(document).ready(function(){
    $('.ask-title').on('click',function(){

    	if($('.ask-body',$(this).closest('.ask-item')).hasClass('close'))
    		{
    			$('.ask-body',$(this).closest('.ask-item')).removeClass('close');
				$('.plus',this).html('-');
    		}
    			else{   $('.ask-body',$(this).closest('.ask-item')).addClass('close');
						$('.plus',this).html('+');
    		}
    })
})