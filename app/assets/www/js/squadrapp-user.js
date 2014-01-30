//	localStorage.user

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
		function loadMe(callback) {
			callback = callback || function(){};
				FB.api('/me', function(response) {
					if (response.error) {
						alert(JSON.stringify(response.error));
					} else {
						var fdata = response;
						$('#content_home').html('');
						fdata.id = fdata.id || '';
						fdata.name = fdata.name || '';
						fdata.first_name = fdata.first_name || '';
						fdata.last_name = fdata.last_name || '';
						fdata.link = fdata.link || '';
						fdata.username = fdata.username || '';
						fdata.hometown = fdata.hometown || {id: '', name: ''};
						fdata.location = fdata.location || {id: '', name: ''};
						fdata.gender = fdata.gender || '';
						fdata.email = fdata.email || '';
						fdata.timezone = fdata.timezone || -5;
						fdata.locale = fdata.locale || 'Bogota, Colombia';
						fdata.updated_time = fdata.updated_time || 0;
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
										squadrapp.user.loadUser(userSquadrapp, function(){
											getMenu();
											callback();
										});
									 }else{
										 alert('Error en el servidor');
									 }
								  });
									$('#overlay').hide();
					}
				});
			}
			
			
			
			function login(callback) {
				callback = callback || function(){};
				FB.login(function(response) {
					if (response.authResponse) {
						loadMe(callback);
					} else {
						alert('not logged in');
					}
				}, {
					scope : "email"
				});
			}
	
						
			function logout(){
				localStorage.removeItem('user');	// Elimina la informacion de usuario logueado
				navigator.app.exitApp(); 
			}
			
			
			
			/*
			 * Validar si el usuario en facebook es diferente al de squadrapp o ya hizo logout
			 */
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
			
			