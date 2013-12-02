// localStorage.user

// El login y los datos de usuario son traidos de facebook
// El origen de estos datos son independientes al manejo del usuario dentro de la aplicacion
// por esto no se agregan estas funciones en este archivo.

/**
 * Copyright SquadrApp.com.
 *
 * @author SquadrApp team
 * @contact Abel Moreno
 * @email amoreno@squadrapp.com
 * @version 0.1
 */

/**
 *
 * Se almacena información en el localStorage, la información almacenada:
 * 
 * 1. Información del usuario logueado
 * 		localStorage.user
 * 
 * 
 * 
 * @package squadrapp
 * @static
 * @access private
 * 
 * 
 */



// Variable donde se encuentra la información del usuario logueado
var user_item;			
if (localStorage.user){
	user_item = JSON.parse(localStorage.getItem('user'));
}else{
	user_item = { login:0 };
}
// Fin carga de usuario logueado


squadrapp = {
	
	/*
	 * @class user
	 * 
	 * 
	 */
	user: {
		
		/*
		 * squadrapp.user.reloadUser();
		 * Recarga la variable de la información del usuario con lo
		 * ultimo guardado en el localStorage.
		 */
		reloadUser: function() {
			if (localStorage.user){
				user_item = JSON.parse(localStorage.getItem('user'));
			}else{
				user_item = { login:0 };
			}
		},
		
		/*
		 * squadrapp.user.isUser();
		 * Indica si existe un usuario logueado o no en la aplicación.
		 */
		isUser: function() {
			if (user_item.login == 1){
				return true;
			}else{
				return false;
			}
		},
		
		/*
		 * squadrapp.user.data;
		 * Acceso para la información del usuario directamente
		 */
		data: function(){
			return user_item;
		},
		
		getImageUrl: function(img_width, img_height){
			img_width = img_width || 120;
			img_height = img_height || 120;
			return "https://graph.facebook.com/"+user_item.facebook_id+"/picture?width="+img_width+"&height="+img_height+"";
		}
		
		
		
		
	}
	
	
};

