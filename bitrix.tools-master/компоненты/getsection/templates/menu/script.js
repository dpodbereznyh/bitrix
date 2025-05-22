$(document).ready(function(){
  			var mouseenter_catalog=false;
			var mouseleave_catalog=true;
			var mouseenter_bigmenu=false;
			var mouseleave_bigmenu=true;
			var intId;

			$('#art-catalog-button').on('mouseenter',function(){
        			$('.big-menu').addClass('hover');
        			mouseenter_catalog=true;
        			mouseleave_catalog=false;
    			})
			$('#art-catalog-button').on('mouseleave',function(){
        			mouseleave_catalog=true;
        			mouseenter_catalog=false;
    			})
  			
  			$('.big-menu').on('mouseenter',function(){
        			mouseenter_bigmenu=true;
        			mouseleave_bigmenu=false;
    			})
			$('.big-menu').on('mouseleave',function(){
        			mouseleave_bigmenu=true;
        			mouseenter_bigmenu=false;
    			})

			setInterval(function(){
        				if(mouseenter_bigmenu||mouseenter_catalog){}else{
        					$('.big-menu').removeClass('hover');
        			
        				}
        			},1000);



})