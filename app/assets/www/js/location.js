// localStorage.location

/**
 * Copyright SquadrApp.com.
 *
 * @author Equipo SquadrApp
 * @contact Abel Moreno
 * @email amoreno@squadrapp.com
 * @version 0.1
 */
 
 /**
 *
 * Funcionalidades de pago usando interpagos
 * 
 * localStorage.location
 * 
 * 
 * 
 * @package location
 * @static
 * @access private
 * 
 *
 * 
 * 
 */
 
 
 /*
 *	Variables
 */
var location_item;		// Variable donde se encuentra la información de la ubicación del usuario
// end vars	----------------------

location = {
	
	/*
	* interpagos.loadUser(user_id);
	* Carga estructura para manejo de pagos por usuario logueado
	*/
	loadUser: function(user_id, callback){
		callback = callback || function(){};
		var intermagos_storage = Object();
		if (localStorage.interpagos){
			intermagos_storage = JSON.parse(localStorage.getItem('interpagos'));
		}
		if (intermagos_storage[user_id] == undefined){
			intermagos_storage[user_id] = { 
						pagos: {
							proceso: Array()
							, exitoso: Object()
						}
						, tarjetas: Object() 
					};
		}
		interpagos_item = intermagos_storage[user_id];
		localStorage.setItem('interpagos', JSON.stringify(intermagos_storage));
		callback();
	},
	
	/*
	* interpagos.doPayment(value, id_card);
	* 
	*/
	doPayment: function(value, id_card, callback){
		callback = callback || function(){};
		var serv = 'URL-ACCION-SERVIDOR';
		var params = {
			uid: interpagos_item.user.user_id	// Informacion cargada previamente
			, val: value 
			, card: interpagos_item.tarjetas[id_card].interpagos_number
		};
		$.ajax({
			type: "POST",
			url: serv,
		    async: true,
		    data: params,
		    success: function(data){
				/*	Acciones */
				callback();
			}
	    });
	}
	
};