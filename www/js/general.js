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
	
	login();
	
});


// .menu_panel
// Carga el menu en todas las paginas en las que se encuentre la clase .menu_panel
function getMenu(){
	if ($('.menu_panel').length) {
		var $menu;	// Menu
		$menu = $('<div>');
		$menu.load('menu.html');
		$.mobile.activePage.find('#menu_panel').panel('toggle');
		$('.menu_panel').html($menu);
	}
}

// Navegación con ajax
// Llamada para cambiar de pagina
function goToPage(page, move){
	move = move || 'slide';
	//$.mobile.navigate( page, { transition: move} );
	$.mobile.changePage( page );
}
