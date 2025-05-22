$(function(){
	$('.fb_form').submit(function(e){
		e.preventDefault();

		var form_parent = $(this);
		var $that = $(this);
		var formData = new FormData($that.get(0));
		$.ajax({
			type: 'POST',
			url: '/ajax.php',
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				form_parent.html(response);
			}
		});
	});

	$(document).on('click', '.show_pop', function (e) {
		e.preventDefault();
		var pop = $(this).attr('data-pop');
		$(pop).addClass('active');
	});
	$(document).on('click', '.close_pop', function(){
		$('.pop').removeClass('active');
	});
});