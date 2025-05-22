var ajax_address='https://'+window.location.hostname+'/local/ajax/region.php';
var fload=function(){
$.post(ajax_address,[{name:'query',value:$('#regionSearch').val()},{name:'page',value: (window.location.pathname + window.location.search)}]).done(function(res){
if(res.search("background-fill")>0){$('.background-fill').remove();$('body').append(res);}else{
		$('.regions-body').html(res);}
	})
}

$('body').on('click','.regions',function(){
if($('.background-fill').length==0)
{
	$.post(ajax_address,[{name:'query',value:''},{name:'form',value:'Y'},{name:'page',value: (window.location.pathname + window.location.search)}]).done(function(form){
		$('body').append(form);
	})
}
	else{
	$('.background-fill').show();
		}

})


	$('body').on('input','#regionSearch',function(){
fload();
})

	$('body').on('click','.regions-close',function(){
$('.background-fill').hide();
})
