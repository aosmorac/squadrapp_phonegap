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
	var stage_width = $(document).width();
	//login();
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
