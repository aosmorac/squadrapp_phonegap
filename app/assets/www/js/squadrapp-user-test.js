//	localStorage.user

/*
	Libreria manejo del usuario en squadrapp
	Aplicación movil utilizando phonegap
	
	FACEBOOK
	Usa el plugin phonegap-facebook-plugin
	https://github.com/phonegap/phonegap-facebook-plugin
	
	En este archivo no utilizamos el plugin de facebook ya que es para moviles
	este archivo es para pruebas en exploradores
*/

$(function(){
	
	
});

/**
**	Carga información de usuario logueado
**/
					
			function loadMe() {
						// userLogueado se obtiene de facebook
						// Por ser un archivo de prueba se cargan datos por defecto
						var userLogueado={ 
								 login: 1
								//,facebook_id:'736187925'
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
						var serv = 'http://desar.squadrapp.com/app/user/login-facebook';
								$.post(serv, { user: JSON.stringify(userLogueado) }, function (data) {
									 var user = JSON.parse(data);
									 if (user.login==1){
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
											,since:user.use_date
											,birthday:user.use_birthday
											,document_id:user.use_document_id
											,mobile:user.use_mobile
											,telephone:user.use_telephone
											,address:user.use_address
											,available:user.use_available
											};
										squadrapp.user.loadUser(userSquadrapp);
										getMenu();
									 }else{
										 alert('Error en el servidor');
									 }
								  });
			}
			
			
			
			function login(){
				loadMe();
			}
			
			function logout(){
				localStorage.removeItem('user');	// Elimina la informacion de usuario logueado
				localStorage.removeItem('nav');	// Elimina la informacion de usuario logueado
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