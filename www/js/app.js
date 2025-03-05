

function initAnimatedLinkIn() {
	$('a[href^="#"]').click(function(){  
		var id = $(this).attr("href");
		var offset = ($(id).offset().top)-64;
		$('html, body').animate({scrollTop: offset}, 'normal'); 
		return false;  
	}); 
}

function popinOpen(pop="pop_user") {
	$("#ombre").fadeIn(100);
	$("#"+pop).fadeIn(100);
}

function popinClose(pop="pop_user") {
	$("#"+pop).fadeOut(100);
	$("#ombre").fadeOut(200);
}




function initfooterPosition () {
	var content_Height = $(window).height();
	var footer_Height = $("footer").height();
	var footer_Top = $("footer").position().top + footer_Height;

	if (footer_Top < content_Height) {
		$("footer").addClass('stickyfooter');
	}
}





$(function() {

	setInterval("jQuery('.blink').fadeOut(500);jQuery('.blink').fadeIn(500);",0);
	//$(".help").tipTop();
	//$(".equal_height").matchHeight();
	setTimeout(function() { $(".box_fixed").slideUp() }, 5000);
	
	$(".auto_submit").change(function() { $(this).parents("form").submit(); });
	
	$(".btn_close_popin").click(function(){
		$(this).closest(".popin").slideUp(200);
		$("#ombre").fadeOut(200);
	});

	$(document).keyup(function(e) {
		if (e.keyCode == 27) { // escape keycode `27`
			$("#ombre").fadeOut(100);
			$(".popin").hide();
		}
	});


		$(window).scroll(function () {
		if ($(this).scrollTop() > 160) {
			$('.back_to_top').fadeIn();
			$("#zone_nav").addClass('fixed_nav');
		} else {
			$('.back_to_top').fadeOut();
			$("#zone_nav").removeClass('fixed_nav');
		}
	});



	$(".btn_close_popin").click(function(){
		$(this).closest(".popin").slideUp(200);
		$("#ombre").fadeOut(200);
	});
	
	$(".confirm").click(function(){
		
		
		link = $(this).attr('data-link');
		txt = $(this).attr('data-text');

		$(this + ' .popin_text').html(txt);
		

		$('#link_content').attr("href", link);
		
		
		
		$("#pop_confirm_suppression").slideDown();
		$("#ombre").fadeIn(100);
	});

	$("#btn_menu").click(function() {
		$('#zone_nav nav').slideToggle(150);
		$("i", this).toggleClass("fa-bars fa-times-circle");
	});


	initAnimatedLinkIn();
	initfooterPosition();


});



