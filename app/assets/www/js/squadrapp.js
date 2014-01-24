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
//var url_base = 'http://localhost:8080';	// Servidor Local Abel
var url_base = 'http://desar.squadrapp.com';	// Servidor de desarrollo
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
	load: function(callback){
		callback = callback || function(){};
		fields_item = { sports: Object() };	// Revisar ubicacion para inicializar
		if (typeof nav_item == 'undefined' || typeof nav_item.isLoad == 'undefined'){
			if (localStorage.user){
				user_item = JSON.parse(localStorage.getItem('user'));
				if (squadrapp.isOnline()) {
					user_item.contacts = {
						  players: Object()
						, circles: Object()
						, localIds: Array()
						, foreignIds: Array()
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
		callback();
	},
	
	/*
	 * @class user
	 * 
	 * 
	 */
	user: {
		
		/*
		 * squadrapp.user.loadUser(user, callback);
		 * Carga a un usuario y carga el resto de data
		 */
		loadUser: function(user, callback){
			callback = callback || function(){};
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
			squadrapp.load(callback);
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
		
		/*
		 * squadrapp.user.getImageUrl;
		 */
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
			var serv = url_base+'/app/user/set-user-online';
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
			var serv = url_base+'/app/user/get-contacts';
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
					
					var loc = (user_item.location).split(",");
					user_item.contacts.localIds = filterObject(user_item.contacts.players, 'location_name', loc[0]);
					user_item.contacts.foreignIds = filterObject(user_item.contacts.players, 'location_name', loc[0], 'middle', false);
			     }
		    });	
		},
		
		/*
		 * squadrapp.user.getLocalContactsIds();
		 */
		getLocalContactsIds: function(){
			return user_item.contacts.localIds;
		},
		
		/*
		 * squadrapp.user.getForeignContactsIds();
		 */
		getForeignContactsIds: function(){
			return user_item.contacts.foreignIds;
		},
		
		/*
		 * squadrapp.user.getUserCity();
		 */
		getUserCity: function(){
			return user_item.location;
		},
		
		/*
		 * squadrapp.user.getContact();
		 */
		getContact: function(uid){
			return user_item.contacts.players[uid];
		},
		
		/*
		 * squadrapp.user.getContactImageUrl();
		 */
		getContactImageUrl: function(uid, img_width, img_height){
			img_width = img_width || 120;
			img_height = img_height || 120;
			return "https://graph.facebook.com/"+user_item.contacts.players[uid].Faacebook_id+"/picture?width="+img_width+"&height="+img_height+"";
		},
		
		/*
		 * squadrapp.user.getContactImageByFacebookId(facebook_id);
		 */
		getContactImageByFacebookId: function(facebook_id, img_width, img_height){
			img_width = img_width || 120;
			img_height = img_height || 120;
			return "https://graph.facebook.com/"+facebook_id+"/picture?width="+img_width+"&height="+img_height+"";
		},
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
		 * squadrapp.nav.getTalker(id);
		 * Carga información de los usuarios que ha tenido conversaciones de la mas reciente a la mas antigua.
		 */
		getTalker: function(id){
				return nav_item.chat.talkers.list[id];			
		},
		
		/*
		 * squadrapp.nav.loadOldTalkers();
		 * Carga información de los usuarios y la deja almacenada en el dispositivo
		 */
		loadOldTalkers: function(callback){
				nav_item.chat.talkers.oldTalkers = Array();
	            callback = callback || function(){};
				var serv = url_base+'/app/chat/get-last-talkers';
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
		             			if(value.isgroup==1){
		             				nav_item.chat.talkers.list['g'+value.com_group_id] = value;
		             				squadrapp.nav.getGroupInfo(value.com_group_id);
		             			}else{
		             				nav_item.chat.talkers.list[value.id_user] = value;
		             			}
		             			allArray[a] = value; a++;
		             			olders[ot] = value; ot++;
								if ((value.mid)/1 > (nav_item.chat.idNewerMessage)/1) {
									nav_item.chat.idNewerMessage = value.mid;
								}
							});
							nav_item.chat.talkers.oldTalkers = olders;
			             	//nav_item.chat.talkers.list = all;
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
				var serv = url_base+'/app/chat/get-new-talkers';
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
								if(value.isgroup==1){
									loads[li] = 'g'+value.com_group_id; li++;
								}else{
									loads[li] = value.id_user; li++;	
								}
								if(value.isgroup==1)
									{
										nav_item.chat.talkers.list['g'+value.com_group_id] = value;
										squadrapp.nav.getGroupInfo(value.com_group_id);
									}else {
										nav_item.chat.talkers.list[value.id_user] = value;
									}
							
								newers[a] = value;
								allArray[a] = value; a++;
								if ((value.mid)/1 > (nav_item.chat.idNewerMessage)/1) {
									nav_item.chat.idNewerMessage = value.mid;
								}
							});
							nav_item.chat.talkers.newTalkers = newers;
							li=0;
							$.each(nav_item.chat.talkers.list, function( index, value ) {
								
								if(value.isgroup==1){
									if ($.inArray( 'g'+value.id_user, loads ) == -1) {
										nav_item.chat.talkers.list['g'+value.com_group_id] = value; 
										squadrapp.nav.getGroupInfo(value.com_group_id);
										allArray[a] = value; a++;
									}else {
										nav_item.chat.talkers.list['g'+value.com_group_id].chat = value.chat; 
									}
								}else{
									if ($.inArray( value.id_user, loads ) == -1) {
										nav_item.chat.talkers.list[value.id_user] = value; 
										allArray[a] = value; a++;
									}else {
										nav_item.chat.talkers.list[value.id_user].chat = value.chat; 
									}
								}
								
							});								
				             	//nav_item.chat.talkers.list = all;
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
		getChatWithUser: function(talker_id, isgroup, open){
			isgroup = typeof(isgroup) != 'undefined' ? isgroup : 0;
			open = typeof(open) != 'undefined' ? open : 'old';
			if (isgroup == 1){
				if (Object.keys(nav_item.chat.talkers.list['g'+talker_id]).length){
					var chat = nav_item.chat.talkers.list['g'+talker_id].chat;
					if (chat == undefined){
						squadrapp.nav.loadChatByGroup(talker_id);
					}
					chat = nav_item.chat.talkers.list['g'+talker_id].chat;
					if ((chat.messages).length > 0){
						if (open == 'new'){ 
							nav_item.chat.talkers.list['g'+talker_id].chat.olders = nav_item.chat.talkers.list['g'+talker_id].chat.messages;
							nav_item.chat.talkers.list['g'+talker_id].chat.totalMessagesLoaded = (nav_item.chat.talkers.list['g'+talker_id].chat.messages).length;
						}	
						return nav_item.chat.talkers.list['g'+talker_id];
					}else{
						squadrapp.nav.loadChatByGroup(talker_id);
						return nav_item.chat.talkers.list['g'+talker_id];
					}
				}else{
					return 'Group error';	
				}	
			}else {
				if (Object.keys(nav_item.chat.talkers.list[talker_id]).length){
					var chat = nav_item.chat.talkers.list[talker_id].chat;
					if (chat == undefined){
						squadrapp.nav.loadChatByUser(talker_id);
					}
					chat = nav_item.chat.talkers.list[talker_id].chat;
					if ((chat.messages).length > 0){
						if (open == 'new'){ 
							nav_item.chat.talkers.list[talker_id].chat.olders = nav_item.chat.talkers.list[talker_id].chat.messages;
							nav_item.chat.talkers.list[talker_id].chat.totalMessagesLoaded = (nav_item.chat.talkers.list[talker_id].chat.messages).length;
						}	
						return nav_item.chat.talkers.list[talker_id];
					}else{
						squadrapp.nav.loadChatByUser(talker_id);
						return nav_item.chat.talkers.list[talker_id];
					}
				}else{
					return 'User error';	
				}	
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
					var serv = url_base+'/app/chat/load-chat';
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
		 * squadrapp.nav.loadChatByGroup(group_id);
		 * Carga mensajes por usuario
		 */
		loadChatByGroup: function(group_id){
			squadrapp.nav.chattingOn();
			if (Object.keys(nav_item.chat.talkers.list['g'+group_id]).length){
				var chat = nav_item.chat.talkers.list['g'+group_id].chat;
				if (chat == undefined){
					// Creo el objeto de chat por usuario si no existe
					chat = { 
							messages: Array()
							, olders: Array()
							, newers: Array() 
							, idNewerMessage: 0
							, totalMessagesLoaded: 0
					};
					nav_item.chat.talkers.list['g'+group_id].chat = chat;
				}
				if (nav_item.chat.talkers.list['g'+group_id].chat.totalMessagesLoaded <= 0){	// Si no se ha cargado ningun mensaje por ese usuario 
					var serv = url_base+'/app/chat/load-chat-group';
					$.ajax({
						 type: "POST",
						 url: serv,
			             async: false,
			             data: { uid: user_item.id, gid: group_id, timezone: user_item.timezone,  start: nav_item.chat.talkers.list['g'+group_id].chat.totalMessagesLoaded },
			             success: function(data){
			             	var all = JSON.parse(data);
			             	all = all.concat(nav_item.chat.talkers.list['g'+group_id].chat.messages);
			             	nav_item.chat.talkers.list['g'+group_id].chat.messages = all;
			             	nav_item.chat.talkers.list['g'+group_id].chat.totalMessagesLoaded = all.length;
			             	if ( typeof nav_item.chat.talkers.list['g'+group_id].chat.messages[0] !== 'undefined' ) {
			             		nav_item.chat.talkers.list['g'+group_id].chat.idNewerMessage = nav_item.chat.talkers.list['g'+group_id].chat.messages[0].mid;
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
					var serv = url_base+'/app/chat/load-chat';
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
			             	//nav_item.chat.talkers.list[user_id].chat.messages = all;
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
				var serv = url_base+'/app/chat/get-new-messages';
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
		 * squadrapp.nav.loadNewMessagesByGroup(group_id);
		 * Carga mensajes por grupo
		 */
		loadNewMessagesByGroup: function(group_id, callback){
				nav_item.chat.talkers.newTalkers = Array();
	            callback = callback || function(){};
				var serv = url_base+'/app/chat/get-new-messages-group';
				$.ajax({
					 type: "POST",
					 url: serv,
		             async: true,
		             data: { uid: user_item.id, fid: group_id, timezone: user_item.timezone, nid: nav_item.chat.talkers.list['g'+group_id].chat.idNewerMessage },
		             success: function(data){
		             	var list = JSON.parse(data);
		             	if (list.length > 0){
							var all = nav_item.chat.talkers.list['g'+group_id].chat.messages;
		             		all = list.concat(all);
							nav_item.chat.talkers.list['g'+group_id].chat.newers = list;
			             	nav_item.chat.talkers.list['g'+group_id].chat.messages = all;
			             	nav_item.chat.talkers.list['g'+group_id].chat.totalMessagesLoaded = all.length;
			             	if ( typeof nav_item.chat.talkers.list['g'+group_id].chat.newers[0] !== 'undefined' ) {
			             		nav_item.chat.talkers.list['g'+group_id].chat.idNewerMessage = nav_item.chat.talkers.list['g'+group_id].chat.messages[0].mid;
			             	}
			             	localStorage.setItem('nav', JSON.stringify(nav_item));
			            	callback();
						}
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
		 * squadrapp.nav.getNewMessagesByGroup(group_id);
		 * Carga ultimos mensajes de la conversacion con el grupo al cual corresponde el id
		 */
		getNewMessagesByGroup: function(group_id){
			if (Object.keys(nav_item.chat.talkers.list['g'+group_id]).length){
				var messages = Array();
					if (nav_item.chat.talkers.list['g'+group_id].chat.newers){
						messages = nav_item.chat.talkers.list['g'+group_id].chat.newers;
					}
					nav_item.chat.talkers.list['g'+group_id].chat.newers = Array();
				return messages;
			}else{
				return 'User error';	
			}	
		},
		
		/*
		 * squadrapp.nav.sendMessageToUser(user_id, message);
		 * Carga mensajes por usuario
		 */
		sendMessageToUser: function(user_id, message, is_group, callback){
				nav_item.chat.talkers.newTalkers = Array();
	            callback = callback || function(){};
				var serv = url_base+'/app/chat/save-message';
				$.ajax({
					 type: "POST",
					 url: serv,
		             async: true,
		             data: { me: user_item.id, to: user_id, isGroup:is_group, msg: message},
		             success: function(data){
			            callback();
					}
	    		});
		},
		
		/*
		 * squadrapp.nav.newChat(user_ids, message, callback);
		 * Crea una nueva conversación, 
		 * Si es a un usuario, envia el mensaje a ese usuario,
		 * Si es a varios usuarios, crea un nuevo grupo y envia el mensaje al grupo
		 */
		newChat: function(user_ids, message, callback){
	            callback = callback || function(){};
				var serv = url_base+'/app/chat/new-chat';
				var group_id = 0;
				$.ajax({
					 type: "POST",
					 url: serv,
			         async: true,
			         data: { me: user_item.id, to: user_ids, msg: message},
			         success: function(data){
			         	group_id = data;
			         	squadrapp.nav.loadNewTalkers(function(){
			         		if (group_id>0){
			         			goChatEvent(group_id, 1);	/*group_id var creada en squadrapp.nav.newChat*/
			         		}
				     		callback();
			         	});
			         	/*$('#new-message').html(data);*/
					 }
		    	});
		},
		
		/*
		 * squadrapp.nav.getActiveChatId();
		 * 
		 */
		getActiveChatId: function(){
			if (nav_item.chat.isWork == 1){
				return nav_item.chat.idChat;
			}else{
				return 0;
			}
		},
		
		/*
		 * squadrapp.nav.getGroupInfo(group_id, callback);
		 * Traer Info de un grupo especifico
		 */
		getGroupInfo: function(group_id, callback){
	            callback = callback || function(){};
				var serv = url_base+'/app/chat/get-group-info';
				$.ajax({
					 type: "POST",
					 url: serv,
			         async: true,
			         data: { gid: group_id},
			         success: function(data){
			         	var g = JSON.parse(data);
			         	nav_item.chat.talkers.list['g'+group_id].com_group_name = g.name;
			         	nav_item.chat.talkers.list['g'+group_id].group_name = g.name;
			         	nav_item.chat.talkers.list['g'+group_id].group_max_user = g.max_users;
			         	nav_item.chat.talkers.list['g'+group_id].group_owner = g.owner;
			         	nav_item.chat.talkers.list['g'+group_id].group_users = g.users;
			         	callback();
					 }
		    	});
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
			var serv = url_base+'/app/fields/load-fields';
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





/* Utilidades */


/*
 * 
 * orderObject(obj, index, cardinality, rType='object')
 * Ordenar objeto por campo especifico
 * Devuelve arreglo de indices ordenados
 */
function orderObject(obj, index, cardinality){
	cardinality = cardinality || 'ASC';
	callback = callback || function(){};
	var ind = Array();
	var elements = Object();
	var result = Array();
	$.each( obj, function( key, element ) {
		elements[element[index]] = key;
		ind.push(element[index]);
	});
	ind.sort();
	if (cardinality == 'ASC'){
		$.each( ind, function( key, element ) {
			result.push(elements[element]);
		});
		return result;
	}else if (cardinality == 'DESC'){
		ind.reverse();
		$.each( ind, function( key, element ) {
			result.push(elements[element]);
		});
		return result;
	}
}

/*
 * 
 * filterObject(obj, index, value, type='pre', match=true)
 * Filtra un objeto por el valor de un campo especifico
 * Devuelve arreglo de indices filtrados
 * type = exact | middle
 */
function filterObject(obj, index, value, type, match){
	type = type || 'middle';
	match = typeof(match) != 'undefined' ? match : true;
	var result = Array();
	value = normalize(value);
	if (match) {
		$.each( obj, function( key, element ) {
			switch(type){
				case 'exact':
					if (normalize(element[index]) == value){
						result.push(key);
					}
				break; 
				case 'middle':
					var first = normalize(element[index]).indexOf(value); 
					if (first > -1){
						result.push(key);
					}
				break;
			}
		});
	}
	else if (!match) {
		$.each( obj, function( key, element ) {
			switch(type){
				case 'exact':
					if (normalize(element[index]) != value){
						result.push(key);
					}
				break; 
				case 'middle':
					var first = normalize(element[index]).indexOf(value);
					if (first == -1){
						result.push(key);
					}
				break;
			}
		});
	}
	return result;
}



/*
 * Remueve acentos
 */
normalize = function(str){
    var defaultDiacriticsRemovalMap = [
    {'base':'A', 'letters':/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g},
    {'base':'AA','letters':/[\uA732]/g},
    {'base':'AE','letters':/[\u00C6\u01FC\u01E2]/g},
    {'base':'AO','letters':/[\uA734]/g},
    {'base':'AU','letters':/[\uA736]/g},
    {'base':'AV','letters':/[\uA738\uA73A]/g},
    {'base':'AY','letters':/[\uA73C]/g},
    {'base':'B', 'letters':/[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g},
    {'base':'C', 'letters':/[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g},
    {'base':'D', 'letters':/[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g},
    {'base':'DZ','letters':/[\u01F1\u01C4]/g},
    {'base':'Dz','letters':/[\u01F2\u01C5]/g},
    {'base':'E', 'letters':/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g},
    {'base':'F', 'letters':/[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g},
    {'base':'G', 'letters':/[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g},
    {'base':'H', 'letters':/[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g},
    {'base':'I', 'letters':/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g},
    {'base':'J', 'letters':/[\u004A\u24BF\uFF2A\u0134\u0248]/g},
    {'base':'K', 'letters':/[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g},
    {'base':'L', 'letters':/[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g},
    {'base':'LJ','letters':/[\u01C7]/g},
    {'base':'Lj','letters':/[\u01C8]/g},
    {'base':'M', 'letters':/[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g},
    {'base':'N', 'letters':/[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g},
    {'base':'NJ','letters':/[\u01CA]/g},
    {'base':'Nj','letters':/[\u01CB]/g},
    {'base':'O', 'letters':/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g},
    {'base':'OI','letters':/[\u01A2]/g},
    {'base':'OO','letters':/[\uA74E]/g},
    {'base':'OU','letters':/[\u0222]/g},
    {'base':'P', 'letters':/[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g},
    {'base':'Q', 'letters':/[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g},
    {'base':'R', 'letters':/[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g},
    {'base':'S', 'letters':/[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g},
    {'base':'T', 'letters':/[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g},
    {'base':'TZ','letters':/[\uA728]/g},
    {'base':'U', 'letters':/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g},
    {'base':'V', 'letters':/[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g},
    {'base':'VY','letters':/[\uA760]/g},
    {'base':'W', 'letters':/[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g},
    {'base':'X', 'letters':/[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g},
    {'base':'Y', 'letters':/[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g},
    {'base':'Z', 'letters':/[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g},
    {'base':'a', 'letters':/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
    {'base':'aa','letters':/[\uA733]/g},
    {'base':'ae','letters':/[\u00E6\u01FD\u01E3]/g},
    {'base':'ao','letters':/[\uA735]/g},
    {'base':'au','letters':/[\uA737]/g},
    {'base':'av','letters':/[\uA739\uA73B]/g},
    {'base':'ay','letters':/[\uA73D]/g},
    {'base':'b', 'letters':/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
    {'base':'c', 'letters':/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
    {'base':'d', 'letters':/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
    {'base':'dz','letters':/[\u01F3\u01C6]/g},
    {'base':'e', 'letters':/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
    {'base':'f', 'letters':/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
    {'base':'g', 'letters':/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
    {'base':'h', 'letters':/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
    {'base':'hv','letters':/[\u0195]/g},
    {'base':'i', 'letters':/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
    {'base':'j', 'letters':/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
    {'base':'k', 'letters':/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
    {'base':'l', 'letters':/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
    {'base':'lj','letters':/[\u01C9]/g},
    {'base':'m', 'letters':/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
    {'base':'n', 'letters':/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
    {'base':'nj','letters':/[\u01CC]/g},
    {'base':'o', 'letters':/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
    {'base':'oi','letters':/[\u01A3]/g},
    {'base':'ou','letters':/[\u0223]/g},
    {'base':'oo','letters':/[\uA74F]/g},
    {'base':'p','letters':/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
    {'base':'q','letters':/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
    {'base':'r','letters':/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
    {'base':'s','letters':/[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
    {'base':'t','letters':/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
    {'base':'tz','letters':/[\uA729]/g},
    {'base':'u','letters':/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
    {'base':'v','letters':/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
    {'base':'vy','letters':/[\uA761]/g},
    {'base':'w','letters':/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
    {'base':'x','letters':/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
    {'base':'y','letters':/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
    {'base':'z','letters':/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g}
  ];

  for(var i=0; i<defaultDiacriticsRemovalMap.length; i++) {
    str = str.replace(defaultDiacriticsRemovalMap[i].letters, defaultDiacriticsRemovalMap[i].base);
  }
  
  						var r=str.toLowerCase();
                        r = r.replace(new RegExp("\\s", 'g'),"");
                        r = r.replace(new RegExp("[àáâãäå]", 'g'),"a");
                        r = r.replace(new RegExp("æ", 'g'),"ae");
                        r = r.replace(new RegExp("ç", 'g'),"c");
                        r = r.replace(new RegExp("[èéêë]", 'g'),"e");
                        r = r.replace(new RegExp("[ìíîï]", 'g'),"i");
                        r = r.replace(new RegExp("ñ", 'g'),"n");                            
                        r = r.replace(new RegExp("[òóôõö]", 'g'),"o");
                        r = r.replace(new RegExp("œ", 'g'),"oe");
                        r = r.replace(new RegExp("[ùúûü]", 'g'),"u");
                        r = r.replace(new RegExp("[ýÿ]", 'g'),"y");
                        r = r.replace(new RegExp("\\W", 'g')," ");
                        return r;

  return str;

                };