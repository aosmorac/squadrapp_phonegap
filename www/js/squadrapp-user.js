/*
	Libreria manejo del usuario en squadrapp
	Aplicación movil utilizando phonegap
	
	FACEBOOK
	Usa el plugin phonegap-facebook-plugin
	https://github.com/phonegap/phonegap-facebook-plugin
*/

$(function(){
	
	
});

/**
**	Carga información de usuario logueado
**/
		function loadMe() {
				FB.api('/me', function(response) {
					if (response.error) {
						alert(JSON.stringify(response.error));
					} else {alert('Cargando Usuario');
						var fdata = response;
						$('#content_home').html('');
						var userLogueado={ 
								 login: 1
								,facebook_id:fdata.id
								,name:fdata.name
								,first_name:fdata.first_name
								,last_name:fdata.last_name
								,facebook_link:fdata.link
								,facebook_username:fdata.username
								,hometown_id:fdata.hometown.id
								,hometown:fdata.hometown.name
								,location_id:fdata.location.name
								,location:fdata.location.name
								,gender:fdata.gender
								,email:fdata.email
								,timezone:fdata.timezone
								,locale:fdata.locale
								,facebook_update_time:fdata.updated_time
								};
								
							var serv = 'http://squadrapp.com/app/user/login-facebook';
								$.post(serv, { user: JSON.stringify(userLogueado) }, function (data) {
									 var user = JSON.parse(data);
									 if (user.login=1){
										 var userSquadrapp={ 
											 login:user.login
											,id:user.id_user
											,facebook_id:user.Facebook_id
											,name:user.use_name
											,first_name:user.use_first_name
											,last_name:user.use_last_name
											,facebook_link:user.Facebook_link
											,facebook_username:user.Facebook_username
											,hometown_id:user.use_hometown_id
											,hometown:user.use_hometown_name
											,location_id:user.use_location_id
											,location:user.use_location_name
											,coordinates:user.use_location_coordinates
											,gender:user.use_gener
											,email:user.use_email
											,timezone:user.timezone
											,locale:user.use_loacale
											};
										localStorage.setItem('user', JSON.stringify(userSquadrapp));		// Almacena la información del usuario logueado
										SAVED_USER = JSON.parse(localStorage.getItem('user'));
										$('.content').html('');
										$.each( SAVED_USER, function( key, value ) {
										  $('.content').append( key + ": " + value + "<br>" );
										});
										$('.content').append('<br><a href="#" onClick="logout();">Log Out</a><p>');
									 }else{
										 alert('Error en el servidor');
									 }
								  });
									$('#overlay').hide();
					}
				});
			}
			
		function setLoadMe(){
					var userLogueado = JSON.parse(localStorage.getItem('userLogueado'));							
						if (userLogueado.login == 1) {
								var serv = 'http://squadrapp.com/app/user/login-facebook';
								$.post(serv, { user: localStorage.getItem('userLogueado') }, function (data) {
									 var user = JSON.parse(data);
									 if (user.login=1){
										 var userSquadrapp={ 
											 login:user.login
											,id:user.id_user
											,facebook_id:user.Facebook_id
											,name:user.use_name
											,first_name:user.use_first_name
											,last_name:user.use_last_name
											,facebook_link:user.Facebook_link
											,facebook_username:user.Facebook_username
											,hometown_id:user.use_hometown_id
											,hometown:user.use_hometown_name
											,location_id:user.use_location_id
											,location:user.use_location_name
											,coordinates:user.use_location_coordinates
											,gender:user.use_gener
											,email:user.use_email
											,timezone:user.timezone
											,locale:user.use_loacale
											};
									localStorage.setItem('user', JSON.stringify(userSquadrapp));		// Almacena la información del usuario logueado
									SAVED_USER = JSON.parse(localStorage.getItem('user'));
									$('.content').html('');
									$.each( SAVED_USER, function( key, value ) {
									  $('.content').append( key + ": " + value + "<br>" );
									});
									$('.content').append('<p>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br><a href="#" onClick="logout();">Log Out</a><p>');
									$('#overlay').hide();
									 }else{
										 alert('Error al ingresar, intentelo nuevamente mas tarde');
										 $('#overlay').hide();
										 localStorage.removeItem('user');
									 }
								});
							}else{
								 alert('Error al ingresar, intentelo nuevamente mas tarde');
								 $('#overlay').hide();
							 }
						localStorage.removeItem('userLogueado');
		}
			
			function loadMeA() {
						var user={ 
								 login: 1
								,facebook_id:'736187925'
								,name:'Abel'
								,first_name:'Abel'
								,last_name:'Moreno'
								,facebook_link:''
								,facebook_username:''
								,hometown_id:''
								,hometown:''
								,location_id:'1'
								,location:'Bogotá'
								,gender:''
								,email:''
								,timezone:'-5'
								,locale:''
								,facebook_update_time:''
								};
						localStorage.setItem('userLogueado', JSON.stringify(user));		// Almacena la información del usuario logueado
			}
			
			function login() {
				FB.login(function(response) {
					if (response.authResponse) {
						loadMe();
					} else {
						alert('not logged in');
					}
				}, {
					scope : "email"
				});
			}
	
			function loginaaaa() {
				FB.login(function(response) {alert('log-in');
					if (response.authResponse) {
						loadMe();
						var userLogueado = JSON.parse(localStorage.getItem('userLogueado'));							
						if (userLogueado.login == 1) {
								var serv = 'http://squadrapp.com/app/user/login-facebook';
								$.post(serv, { user: localStorage.getItem('userLogueado') }, function (data) {
									 var user = JSON.parse(data);
									 if (user.login=1){
										 var userSquadrapp={ 
											 login:user.login
											,id:user.id_user
											,facebook_id:user.Facebook_id
											,name:user.use_name
											,first_name:user.use_first_name
											,last_name:user.use_last_name
											,facebook_link:user.Facebook_link
											,facebook_username:user.Facebook_username
											,hometown_id:user.use_hometown_id
											,hometown:user.use_hometown_name
											,location_id:user.use_location_id
											,location:user.use_location_name
											,coordinates:user.use_location_coordinates
											,gender:user.use_gener
											,email:user.use_email
											,timezone:user.timezone
											,locale:user.use_loacale
											};
									localStorage.setItem('user', JSON.stringify(userSquadrapp));		// Almacena la información del usuario logueado
									SAVED_USER = JSON.parse(localStorage.getItem('user'));
									$('.content').html('');
									$.each( SAVED_USER, function( key, value ) {
									  $('.content').append( key + ": " + value + "<br>" );
									});
									$('.content').append('<p>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br><a href="#" onClick="logout();">Log Out</a><p>');
									$('#overlay').hide();
									 }else{
										 alert('Error al ingresar, intentelo nuevamente mas tarde');
										 $('#overlay').hide();
										 localStorage.removeItem('user');
									 }
								});
							}else{
								 alert('Error al ingresar, intentelo nuevamente mas tarde');
								 $('#overlay').hide();
							 }
						localStorage.removeItem('userLogueado');
					} else {
						alert('not logged in');
					}
				}, {
					scope : "email"
				});
			}
			
			function loginA(){
				loadMeA();
				var userLogueado = JSON.parse(localStorage.getItem('userLogueado'));							
					if (userLogueado.login == 1) {
								var serv = 'http://squadrapp.com/app/user/login-facebook';
								$.post(serv, { user: localStorage.getItem('userLogueado') }, function (data) {
									 var user = JSON.parse(data);
									 if (user.login=1){
										 var userSquadrapp={ 
											 login:user.login
											,id:user.id_user
											,facebook_id:user.Facebook_id
											,name:user.use_name
											,first_name:user.use_first_name
											,last_name:user.use_last_name
											,facebook_link:user.Facebook_link
											,facebook_username:user.Facebook_username
											,hometown_id:user.use_hometown_id
											,hometown:user.use_hometown_name
											,location_id:user.use_location_id
											,location:user.use_location_name
											,coordinates:user.use_location_coordinates
											,gender:user.use_gener
											,email:user.use_email
											,timezone:user.timezone
											,locale:user.use_loacale
											};
									localStorage.setItem('user', JSON.stringify(userSquadrapp));		// Almacena la información del usuario logueado
									seeUser();
									$('#overlay').hide();
									 }else{
										 localStorage.removeItem('user');
										 alert('Error al ingresar, intentelo nuevamente mas tarde');
										 $('#overlay').hide();
									 }
								});
					 }else{
						 alert('Error al ingresar, intentelo nuevamente mas tarde');
						 $('#overlay').hide();
					 }
				localStorage.removeItem('userLogueado');
			}
			
			function logout(){
				localStorage.removeItem('user');	// Elimina la informacion de usuario logueado
				alert('logout');
			}
			
			function validateFacebookUser(command){
				if(command == 'start'){
					timerChat = setInterval(function () {
					/*
					** Si usuario no coincide realizar logout
					*/
					if ( localStorage.user )
					{
						saved_user = JSON.parse(localStorage.getItem('user'));
						FB.getLoginStatus(function(response) {
						  if (response.status === 'connected') {
							var uid = response.authResponse.userId;
							//alert (uid+' -> '+saved_user.facebook_id)
							if (uid != saved_user.facebook_id) {
								logout();
							}
						  } else if (response.status === 'not_authorized') {
							logout();
						  } else {
							logout();
						  }
						 }, true);
					}else{
						saved_user = {login: 0};
					}
					/******************************************/
					}, 30000); 
				}
			}
			
			
			function seeUser(){
					if (SAVED_USER.login == 1){
						$('.content').html('');
						$.each( SAVED_USER, function( key, value ) {
						  $('.content').append( key + ": " + value + "<br>" );
						});
						$('.content').append('<p>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br><a href="#" onClick="logout();">Log Out</a><p>');
					}
			}