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
 * 2. Información de la navegación, incluido el chat
 * 		localStorage.nav
 * 		local.Storage.nav.chat
 * 
 * 
 * 
 * @package squadrapp
 * @static
 * @access private
 * 
 * Elementos
 * @class user
 * @class nav 
 * @class fields
 * 
 */




/*
 *	Variables de información de la aplicació 
 */
var user_item;		// Variable donde se encuentra la información del usuario logueado
var nav_item;		// Variable donde se encuentra la información de navegacion (chat y secciones)	
var fields_item;	// Variable donde se encuentra la información de los lugares para practicar deporte.
// end vars	----------------------


squadrapp = {
	
	/*
	* squadrapp.isOnline();
	* Revisa la conexión a internet y devuelve verdadero o falso
	*/
	isOnline: function(){
		return true;
	},
	
	/*
	* squadrapp.load();
	* Carga la información inicial
	*/
	load: function(){
		fields_item = { sports: Object() };	// Revisar ubicacion para inicializar
		if (typeof nav_item == 'undefined' || typeof nav_item.isLoad == 'undefined'){
			if (localStorage.user){
				user_item = JSON.parse(localStorage.getItem('user'));
				if (squadrapp.isOnline()) {
					user_item.contacts = {
						  players: Object()
						, circles: Object()
					};
					squadrapp.user.getContacts();
				}
				if (localStorage.nav){
					nav_item = JSON.parse(localStorage.getItem('nav'));
				}else{
					nav_item = { 
						chat: { 
							isWork: 0
							, talkers: { 
								older: 0
								, list: Object() 
								, oldTalkers: Array() 
								, newTalkers: Array() 
								, listArray: Array()
							} 
							, isLoad: 0 
							, isChatting: 0
							, idNewerMessage: 0
						}
						, isWork: 1 
						, isLoad: 1 
					};
				}
				localStorage.setItem('nav', JSON.stringify(nav_item));		// Almacena la informaciÃ³n del usuario logueado
				squadrapp.user.updateUserOnline();	// Mantiene al usuario en estado conectado
				//squadrapp.nav.startWork();
				this.nav.clearChat();
				this.nav.loadOldTalkers();
			}else{
				user_item = { login:0 };
				nav_item = { 
						isWork: 0 
						, isLoad: 0 
					};
			}	
		}
	},
	
	/*
	 * @class user
	 * 
	 * 
	 */
	user: {
		
		/*
		 * squadrapp.user.loadUser();
		 * Carga a un usuario y carga el resto de data
		 */
		loadUser: function(user){
			user_item={ 
				login:user.login
				,id:user.id
				,facebook_id:user.facebook_id
				,name:user.name
				,first_name:user.first_name
				,last_name:user.last_name
				,facebook_link:user.facebook_link
				,facebook_username:user.facebook_username
				,hometown_id:user.hometown_id
				,hometown:user.hometown
				,location_id:user.location_id
				,location:user.location
				,coordinates:user.coordinates
				,gender:user.gender
				,email:user.email
				,timezone:user.timezone
				,locale:user.locale
				,since:user.since
				,birthday:user.birthday
				,document_id:user.document_id
				,mobile:user.mobile
				,telephone:user.telephone
				,address:user.address
				,available:user.available
				,lat:''					// Obterner de coordinates
				,lng:''					// Obterner de coordinates
				,city_id:2257			// Obtener en la consulta, si ciudad cambia cambiar id 
				,sport_id:1				// Deporte por defecto, obtener de la consulta 
				,my_sports: Object()	// Obtener por consulta
			};
			localStorage.setItem('user', JSON.stringify(user_item));		// Almacena la informaciÃ³n del usuario logueado
			squadrapp.load();
		},
		
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
		},
		
		/*
		 * squadrapp.user.getUserId();
		 */
		getUserId: function(){
			return user_item.id;
		}, 
		
		/*
		 * squadrapp.user.updateUserOnline();
		 * Actualiza como conectado al usuario logueado y conectado.
		 */
		updateUserOnline: function(){
			var serv = 'http://squadrapp.com/app/user/set-user-online';
			$.ajax({
				 type: "POST",
				 url: serv,
			     async: false,
			     data: { uid: user_item.id },
			     success: function(data){ }
		    });	
		    setTimeout(function () {
				squadrapp.user.updateUserOnline();
			}, 40000); 
		},
		
		/*
		 * squadrapp.user.getContacts();
		 * Trae los usuarios que tiene como amigos o contactos la persona logueada
		 */
		getContacts: function(){
			var serv = 'http://squadrapp.com/app/user/get-contacts';
			$.ajax({
				 type: "POST",
				 url: serv,
			     async: false,
			     data: { uid: user_item.id },
			     success: function(data){ 
			     	var friends = JSON.parse(data);
			     	$.each(friends, function( index, value ) {
			     		user_item.contacts.players[value.id] = value;
					});
			     }
		    });	
		}
		
	},
	
	/*
	 * @class nav
	 * 
	 * 
	 */
	nav: {
		
		/*
		 * squadrapp.nav.isWork();
		 * Indica si la aplicación esta siendo navegada o ha sido navegada por el usuario logueado.
		 */
		isWork: function() {
			if (nav_item.isWork == 1){
				return true;
			}else{
				return false;
			}
		},
		
		/*
		 * squadrapp.nav.startWork();
		 * Indica que el usuario esta navegando.
		 */
		startWork: function(){
			if (nav_item.isWork == 1){
				return true;
			}else{
				nav_item.isWork = 1;
				localStorage.setItem('nav', JSON.stringify(nav_item));
				return true;
			}
		},
		
		/*
		 * squadrapp.nav.isChat();
		 * Indica si esta chateando activamente en este momento.
		 */
		isChat: function() {
			if (nav_item.chat.isWork == 1){
				return true;
			}else{
				return false;
			}
		},
		
		/*
		 * squadrapp.nav.clearChat();
		 * Indica si esta chateando activamente en este momento.
		 */
		clearChat: function() {
			nav_item.chat.isWork = 0;
		},
		
		/*
		 * squadrapp.nav.chattingOn();
		 * Indica si tiene la ventana de un chat especifico abierta.
		 */
		chattingOn: function() {
			nav_item.chat.isChatting = 1;
		},
		
		
		/*
		 * squadrapp.nav.chattingOff();
		 * Indica que el usuario ha salido de un chat especifico
		 */
		chattingOff: function() {
			nav_item.chat.isChatting = 0;
		},
		
		/*
		 * squadrapp.nav.isChatting();
		 * Indica que el usuario ha salido de un chat especifico
		 */
		isChatting: function() {
			if (nav_item.chat.isChatting == 1){
				return true;
			}else {
				return false;
			}
		},
		
		/*
		 * squadrapp.nav.getActiveChatId();
		 * Devuelve el Id del chat que esta activo en ese momento.
		 */
		getActiveChatId: function(){
			if (nav_item.chat.isWork == 1){
				return nav_item.chat.idChat;
			}else{
				return 0;
			}
		},
		
		/*
		 * squadrapp.nav.getTalkers();
		 * Carga información de los usuarios que ha tenido conversaciones de la mas reciente a la mas antigua.
		 */
		getTalkers: function(){
			if (nav_item.chat.isLoad == 1){
				if (nav_item.chat.isWork==0 || (nav_item.chat.talkers.oldTalkers).length==0){
					nav_item.chat.talkers.oldTalkers = nav_item.chat.talkers.listArray;
				}
				nav_item.chat.isWork = 1;
				return nav_item.chat.talkers;	
			}
			return 'call squadrapp.nav.loadOldTalkers();';			
		},
		
		/*
		 * squadrapp.nav.loadOldTalkers();
		 * Carga información de los usuarios y la deja almacenada en el dispositivo
		 */
		loadOldTalkers: function(callback){
				nav_item.chat.talkers.oldTalkers = Array();
	            callback = callback || function(){};
				var serv = 'http://squadrapp.com/app/chat/get-last-talkers';
				$.ajax({
					 type: "POST",
					 url: serv,
		             async: true,
		             data: { uid: user_item.id, timezone: user_item.timezone, start: nav_item.chat.talkers.older },
		             success: function(data){
		             	var list = JSON.parse(data);
		             	var talkers = Object();
		             	if (list.length > 0){
		             		var all = new Object();
		             		var allArray = new Array(); var a = nav_item.chat.talkers.listArray.length;	// Necesario para conservar orden
		             		var olders = new Object();
		             		all = nav_item.chat.talkers.list;
		             		allArray = nav_item.chat.talkers.listArray;
		             		var ot = 0;
		             		$.each(list, function( index, value ) {
		             			all[value.id_user] = value;
		             			allArray[a] = value; a++;
		             			olders[ot] = value; ot++;
								if (value.mid > nav_item.chat.idNewerMessage) {
									nav_item.chat.idNewerMessage = value.mid;
								}
							});
							nav_item.chat.talkers.oldTalkers = olders;
			             	nav_item.chat.talkers.list = all;
			             	nav_item.chat.talkers.listArray = allArray;
				            nav_item.chat.talkers.older = Object.keys(nav_item.chat.talkers.list).length;		// Aumenta segun paginado o cargado			
							nav_item.chat.isLoad = 1;
							localStorage.setItem('nav', JSON.stringify(nav_item));
						}
						callback();
					}
	    		});
		},
		
		/*
		 * squadrapp.nav.loadNewTalkers();
		 * Carga información de los usuarios y la deja almacenada en el dispositivo
		 */
		loadNewTalkers: function(callback){
				nav_item.chat.talkers.newTalkers = Array();
	            callback = callback || function(){};
				var serv = 'http://squadrapp.com/app/chat/get-new-talkers';
				$.ajax({
					 type: "POST",
					 url: serv,
		             async: true,
		             data: { uid: user_item.id, timezone: user_item.timezone, nid: nav_item.chat.idNewerMessage },
		             success: function(data){
		             	var list = JSON.parse(data);
		             	if (list.length > 0){
							var all = new Object();
		             		var allArray = new Array(); var a = 0;	// Necesario para conservar orden
							var loads = new Array();
							var newers = new Array(); 
							var li=0;
							$.each(list, function( index, value ) {
								loads[li] = value.id_user; li++;
								all[value.id_user] = value;
								newers[a] = value;
								allArray[a] = value; a++;
								if (value.mid > nav_item.chat.idNewerMessage) {
									nav_item.chat.idNewerMessage = value.mid;
								}
							});
							nav_item.chat.talkers.newTalkers = newers;
							li=0;
							$.each(nav_item.chat.talkers.list, function( index, value ) {
								if ($.inArray( value.id_user, loads ) == -1) {
									all[value.id_user] = value; 
									allArray[a] = value; a++;
								}else {
									all[value.id_user].chat = value.chat; 
								}
							});								
				             	nav_item.chat.talkers.list = all;
			             		nav_item.chat.talkers.listArray = allArray;
				             	nav_item.chat.talkers.older = Object.keys(nav_item.chat.talkers.list).length;		// Aumenta segun paginado o cargado		
								localStorage.setItem('nav', JSON.stringify(nav_item));
						}
						callback();
					}
	    		});
		},		
		
		/*
		 * squadrapp.nav.getChatWithUser(user_id);
		 * Carga información de los usuarios que ha tenido conversaciones de la mas reciente a la mas antigua.
		 */
		getChatWithUser: function(user_id){
			if (Object.keys(nav_item.chat.talkers.list[user_id]).length){
				var chat = nav_item.chat.talkers.list[user_id].chat;
				if (chat == undefined){
					squadrapp.nav.loadChatByUser(user_id);
				}
				chat = nav_item.chat.talkers.list[user_id].chat;
				if ((chat.messages).length > 0){
					return nav_item.chat.talkers.list[user_id];
				}else{
					squadrapp.nav.loadChatByUser(user_id);
					return nav_item.chat.talkers.list[user_id];
				}
			}else{
				return 'User error';	
			}	
		},
		
		/*
		 * squadrapp.nav.loadChatByUser(user_id);
		 * Carga mensajes por usuario
		 */
		loadChatByUser: function(user_id){
			squadrapp.nav.chattingOn();
			if (Object.keys(nav_item.chat.talkers.list[user_id]).length){
				var chat = nav_item.chat.talkers.list[user_id].chat;
				if (chat == undefined){
					// Creo el objeto de chat por usuario si no existe
					chat = { 
							messages: Array()
							, olders: Array()
							, newers: Array() 
							, idNewerMessage: 0
							, totalMessagesLoaded: 0
					};
					nav_item.chat.talkers.list[user_id].chat = chat;
				}
				if (nav_item.chat.talkers.list[user_id].chat.totalMessagesLoaded <= 0){	// Si no se ha cargado ningun mensaje por ese usuario 
					var serv = 'http://squadrapp.com/app/chat/load-chat';
					$.ajax({
						 type: "POST",
						 url: serv,
			             async: false,
			             data: { uid: user_item.id, fid: user_id, timezone: user_item.timezone,  start: nav_item.chat.talkers.list[user_id].chat.totalMessagesLoaded },
			             success: function(data){
			             	var all = JSON.parse(data);
			             	all = all.concat(nav_item.chat.talkers.list[user_id].chat.messages);
			             	nav_item.chat.talkers.list[user_id].chat.messages = all;
			             	nav_item.chat.talkers.list[user_id].chat.totalMessagesLoaded = all.length;
			             	if ( typeof nav_item.chat.talkers.list[user_id].chat.messages[0] !== 'undefined' ) {
			             		nav_item.chat.talkers.list[user_id].chat.idNewerMessage = nav_item.chat.talkers.list[user_id].chat.messages[0].mid;
			             	}
			             	localStorage.setItem('nav', JSON.stringify(nav_item));
						 }
		    		});	
				}
	    		
			}
			
		}, 
		
		
		/*
		 * squadrapp.nav.loadOldMessagesByUser(user_id);
		 * Carga mensajes por usuario
		 */
		loadOldMessagesByUser: function(user_id, callback){
	            callback = callback || function(){};
	            if (Object.keys(nav_item.chat.talkers.list[user_id]).length){
				var chat = nav_item.chat.talkers.list[user_id].chat;
				if (chat == undefined){
					// Creo el objeto de chat por usuario si no existe
					chat = { 
							messages: Array()
							, olders: Array()
							, newers: Array() 
							, idNewerMessage: 0
							, totalMessagesLoaded: 0
					};
					nav_item.chat.talkers.list[user_id].chat = chat;
				}
				//	Se cargan los siguientes 10 mensajes mas antiguos
					var serv = 'http://squadrapp.com/app/chat/load-chat';
					$.ajax({
						 type: "POST",
						 url: serv,
			             async: false,
			             data: { uid: user_item.id, fid: user_id, timezone: user_item.timezone,  start: nav_item.chat.talkers.list[user_id].chat.totalMessagesLoaded },
			             success: function(data){
			             	var all = nav_item.chat.talkers.list[user_id].chat.messages;
			             	var list = JSON.parse(data);
			             	nav_item.chat.talkers.list[user_id].chat.olders = list;
			             	all = all.concat(list);
			             	nav_item.chat.talkers.list[user_id].chat.messages = all;
			             	nav_item.chat.talkers.list[user_id].chat.totalMessagesLoaded = all.length;
			             	if ( typeof nav_item.chat.talkers.list[user_id].chat.messages[0] !== 'undefined' ) {
			             		nav_item.chat.talkers.list[user_id].chat.idNewerMessage = nav_item.chat.talkers.list[user_id].chat.messages[0].mid;
			             	}
			             	
			             	localStorage.setItem('nav', JSON.stringify(nav_item));
			             	callback();
						 }
		    		});	
	    		
			}
		},
		
		/*
		 * squadrapp.nav.loadNewMessagesByUser(user_id);
		 * Carga mensajes por usuario
		 */
		loadNewMessagesByUser: function(user_id, callback){
				nav_item.chat.talkers.newTalkers = Array();
	            callback = callback || function(){};
				var serv = 'http://squadrapp.com/app/chat/get-new-messages';
				$.ajax({
					 type: "POST",
					 url: serv,
		             async: true,
		             data: { uid: user_item.id, fid: user_id, timezone: user_item.timezone, nid: nav_item.chat.talkers.list[user_id].chat.idNewerMessage },
		             success: function(data){
		             	var list = JSON.parse(data);
		             	if (list.length > 0){
							var all = nav_item.chat.talkers.list[user_id].chat.messages;
		             		all = list.concat(all);
							nav_item.chat.talkers.list[user_id].chat.newers = list;
			             	nav_item.chat.talkers.list[user_id].chat.messages = all;
			             	nav_item.chat.talkers.list[user_id].chat.totalMessagesLoaded = all.length;
			             	if ( typeof nav_item.chat.talkers.list[user_id].chat.newers[0] !== 'undefined' ) {
			             		nav_item.chat.talkers.list[user_id].chat.idNewerMessage = nav_item.chat.talkers.list[user_id].chat.messages[0].mid;
			             	}
			             	localStorage.setItem('nav', JSON.stringify(nav_item));
						}
			            callback();
					}
	    		});
		}, 
		
		/*
		 * squadrapp.nav.getNewMessagesByUser(user_id);
		 * Carga ultimos mensajes de la conversacion con el usuario al cual corresponde el id
		 */
		getNewMessagesByUser: function(user_id){
			if (Object.keys(nav_item.chat.talkers.list[user_id]).length){
				var messages = Array();
					if (nav_item.chat.talkers.list[user_id].chat.newers){
						messages = nav_item.chat.talkers.list[user_id].chat.newers;
					}
					nav_item.chat.talkers.list[user_id].chat.newers = Array();
				return messages;
			}else{
				return 'User error';	
			}	
		},
		
		/*
		 * squadrapp.nav.sendMessageToUser(user_id, message);
		 * Carga mensajes por usuario
		 */
		sendMessageToUser: function(user_id, message, callback){
				nav_item.chat.talkers.newTalkers = Array();
	            callback = callback || function(){};
				var serv = 'http://squadrapp.com/app/chat/save-message';
				$.ajax({
					 type: "POST",
					 url: serv,
		             async: true,
		             data: { me: user_item.id, to: user_id, msg: message},
		             success: function(data){
			            callback();
					}
	    		});
		},
		
		/*
		 * squadrapp.nav.updateReadMessages(user_id);
		 * Cambia estado de mensajes a leidos
		 */
		updateReadMessages: function(user_id, callback){
	            callback = callback || function(){};
	            if (squadrapp.nav.isChatting()) {
					var serv = 'http://squadrapp.com/app/chat/update-read-messages';
					$.ajax({
						 type: "POST",
						 url: serv,
			             async: true,
			             data: { me: user_item.id, to: user_id},
			             success: function(data){
				            callback();
						}
		    		});
	    		}
		}
		
		
		
	},
	
	fields: {
		
		/*
		 * squadrapp.fields.loadFields();
		 * Carga las canchas, centro deportivos o espacios para praticar algun deporte
		 */
		loadFields: function(callback){
			if ( typeof fields_item == 'undefined' || typeof fields_item.sports == 'undefined' ){
				fields_item = { sports: Object() };
			}
			if ( typeof fields_item.sports[user_item.sport_id] == 'undefined' ){
				fields_item.sports[user_item.sport_id] = Object();
			}
			if ( typeof fields_item.sports[user_item.sport_id].cities == 'undefined' ){
				fields_item.sports[user_item.sport_id] = { cities: Object() };
			}
			if ( typeof fields_item.sports[user_item.sport_id].cities[user_item.city_id] == 'undefined' ){
				fields_item.sports[user_item.sport_id].cities[user_item.city_id] = { places: Object() };
			}
			var serv = 'http://squadrapp.com/app/fields/load-fields';
			$.ajax({
				 type: "POST",
				 url: serv,
			     async: false,
			     data: { cid: user_item.city_id, sid: user_item.sport_id },
			     success: function(data){
			     	var places = JSON.parse(data);
					fields_item.sports[user_item.sport_id].cities[user_item.city_id].places = places;
					fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.lastLoad = -1;
					callback();
				 }
		    });	
		}, 
		
		/*
		 * squadrapp.fields.resetPages();
		 * Vuelve a la pagina inicial
		 */
		resetPages: function(){
			fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.lastLoad = -1;
		},
		
		/*
		 * squadrapp.fields.getNextFields();
		 * retorna un arreglo por orden.
		 */
		getNextFields: function(){
			var f = Array();	//	Arreglo a retornar
			var filter = fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.filter;
			var f_index = 0;
			var nextLoad = fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.lastLoad+1;
			for (i=nextLoad; i<nextLoad+3; i++) {
				if ( typeof fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.list[filter[i]] == 'undefined'){
					
				}else{
					f[f_index] = fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.list[filter[i]];
					fields_item.sports[user_item.sport_id].cities[user_item.city_id].places.lastLoad = i;
					f_index++;
				}
			}
			return f;
		}
		
	}
	
	
};