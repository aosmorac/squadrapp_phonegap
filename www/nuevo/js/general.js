// Structura y movimiento del sitio
// Menu, paneles, efectos, etc...

/**
 * Copyright SquadrApp.com.
 *
 * @author SquadrApp team
 * @contact Abel Moreno
 * @email amoreno@squadrapp.com
 * @version 0.1
 */

$(function(){
	var stage_width = $(document).width(),
		stage_height = $(document).height();
	//login();
	$('#chat').css({top:stage_height, height : stage_height});
	$.mobile.activePage.find('#chat_icon').css('left',((stage_width/2)-25));
	$('#chat_icon').click(function(){
		if(($(this).data("estado")) == "quiet"){
			$('#chat').animate({
				top: (44)
			}
				, 500);
			$('#chat_icon').animate({
				top : -(stage_height-60)
			},500);
			$(this).data("estado",'active');
		}else{
			$('#chat').animate({
				top: stage_height
			}
				, 500);
			$('#chat_icon').animate({
				top : -25
			});
			$(this).data("estado",'quiet');
		}
	})
	$(document).bind("mobileinit", function(){

		$.mobile.touchOverflowEnabled = false;

		$.mobile.defaultPageTransition = 'none';

		$.mobile.defaultDialogTransition = 'none';

		$.mobile.useFastClick = false

		$.mobile.buttonMarkup.hoverDelay = 0;

		$.mobile.page.prototype.options.domCache = false;

		$.event.special.swipe.scrollSupressionThreshold = 100;

	});
});


// .menu_panel
// Carga el menu en todas las paginas en las que se encuentre la clase .menu_panel
function getMenu(){
	if ($('.menu').length) {
		$('.menu').load('menu.html');
		$.mobile.activePage.find('#menu').panel('toggle');
	}
}

// Navegación con ajax
// Llamada para cambiar de pagina
function goToPage(page, move){
	move = move || 'slide';
	//$.mobile.navigate( page, { transition: move} );
	$.mobile.changePage( page );
}
