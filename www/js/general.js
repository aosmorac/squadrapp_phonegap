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

var stage_width, stage_height;
var btn_chat_position;
var timerChatList = null;

var scrollChatList;	// Scroll chat list
var scrollChatWith;	// Scroll chat con otro usuario


$(function(){

					login();
				
	$('.overlay').hide();
	$('img').load(function() {
		$(this).show(); //muestra el div despues de que la imagen carga.
	});
			
	
	    stage_width = $(document).width(),
		stage_height = $(document).height();
	$('#chat').css({top: stage_height});
	$('#chat-bg').css({top: stage_height});
	$('#menu').height($(window).height());
	//$( "#chat_icon" ).draggable();
	//login();
	
	
	
	var elem = document.getElementById('chat_section');
	window.mySwipe = Swipe(elem, {
	  // startSlide: 4,
	  // auto: 3000,
	  // continuous: true,
	     disableScroll: true,
	  // stopPropagation: true,
	  // callback: function(index, element) {},
	  // transitionEnd: function(index, element) {}
	});
		
	

});


document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);



function clickBtnChat(){
	if(($('#chat_icon').data("estado")) == "quiet"){
		openChatSection(function(){
			if (squadrapp.nav.isWork()){	// Valida que el usuario este adentro
				if (squadrapp.nav.isChat()){
					// Carga el chat en el que esta conversando
					scrollChatList.refresh();
				}else {	// Carga la lista de los ultimos chats
						getChatList(function() {
							$(".overlay_chat").hide();
						});
				}
			}
			else{
			}
		});
	}else{
		closeChatSection();
	}
}

function closeChatSection(){
			$('#chat_section').animate({
				top: $(window).height()
			}, 400);
			/*$('#chat_icon').animate({
				top : btn_chat_position.top, left: btn_chat_position.left
			});*/
			$('#chat_icon').animate({
				top : ($(window).height())-60
			});
			$('#chat-bg').css({top: stage_height});
			$('#chat_icon').removeClass( "chat-close" );
			$('#chat_icon').addClass( "chat-open" );
			$('#chat_icon .arrow').hide();
			$('#chat_icon').data("estado",'quiet');
}
function openChatSection(callback){
			$('#chat_section').animate({
				top: (70)
			}, 500, function(){
				callback();
			});
			$('#chat_icon').animate({
				top: (10)},500);
			$('#chat-bg').css({top: 0});
			$('#chat_icon').removeClass( "chat-open" );
			$('#chat_icon').addClass( "chat-close" );
			$('#chat_icon .arrow').show();
			$('#chat_icon').data("estado",'active');
}

// .menu_panel
// Carga el menu en todas las paginas en las que se encuentre la clase .menu_panel
function getMenu(callback){
	callback = callback || function(){};
	if ($('.menu').length) {
		$('.menu').load('menu.html', function() {
			user_data = squadrapp.user.data();
			$('#user-menu .user-image-70x70').html('<img src="'+squadrapp.user.getImageUrl(70,70)+'" width="70" height="70" />');
			$('#user-menu .info').html('<p>'+truncate(user_data.name,15,"")+'</p><p>'+truncate(user_data.hometown,15,"")+'</p>');
			loadImages();
		});
		callback();
	}
}
function openMenu(){
	getMenu(function(){
		snapper.open('left');
	});
}

// Navegación con ajax
// Llamada para cambiar de pagina
function goToPage(page, move){
	move = move || 'slide';
	//$.mobile.navigate( page, { transition: move} );
	$.mobile.changePage( page );
}



// Cargar ultimas conversaciones
function getChatList(callback){
	$(".overlay_chat").show();
	callback = callback || function(){};
	$('#content-chat-section').load('chat-list.html', function() {
		var pullDownEl = document.getElementById('pullDown-list-chats');
		var pullDownOffset = pullDownEl.offsetHeight;
		var pullUpEl = document.getElementById('pullUp-list-chats');	
		var pullUpOffset = pullUpEl.offsetHeight;
		$('#chat-list').height(($('#chat_section').height()));
			scrollChatList = new iScroll('scroll-list-chats', {
				onScrollMove: function () {
				if (this.y > 5 && !pullDownEl.className.match('flip')) {
					pullDownEl.className = 'flip';
					this.minScrollY = 0;
				} else if (this.y < 5 && pullDownEl.className.match('flip')) {
					pullDownEl.className = '';
					this.minScrollY = -pullDownOffset;
				} else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
					pullUpEl.className = 'flip';
				} else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
					pullUpEl.className = '';
				}
			},
			onScrollEnd: function () {
				if (pullDownEl.className.match('flip')) {
					pullDownEl.className = '';
					$('#list-chats').prepend('<div class="loader-chat-list" align="center"><img src="img/loader.gif" style="display: inherit;" /></div>');
					scrollChatListEvent('down');
				} else if (pullUpEl.className.match('flip')) {
					$('.loader-chat-list').remove();
					pullUpEl.className = 'flip';
					$('#list-chats').append('<div class="loader-chat-list" align="center"><img src="img/loader.gif" style="display: inherit;" /></div>');
				    //scrollChatList.refresh();
					scrollChatListEvent('up');
				}
			}
		});
		if ($("#list-chats").length) {
			$("#list-chats").html('<div align="center" style="padding: 50px 0;"><img src="img/loader.gif" /></div>');
			talkers = squadrapp.nav.getTalkers();
			$("#list-chats").html('');
			$.each(talkers.oldTalkers, function( index, value ) {
				if (value != undefined && value != null){
					if (!$('#talker-'+value.id_user).length){
						$("#list-chats").append('<section onClick="goChatEvent('+value.id_user+');" class="talker" id="talker-'+value.id_user+'"><div class="image user-image-60x60"><img width="60" height="60" src="https://graph.facebook.com/'+value.Facebook_id+'/picture?width=60&height=60"></div><section class="content"><h3>'+truncate(value.use_name,15,"")+'</h3><p>'+truncate(value.message)+'</p></section><section class="date">'+formatChatDate(value.date)+'</section><div class="corte"> </div></section>');
					}
				}
			});
			$('img').load(function() {
				$(this).show(); //muestra el div despues de que la imagen carga.
			});
			scrollChatListEvent('on');
			autoLoadChatList('start');
		}
		$(".overlay_chat").hide();
		scrollChatList.refresh();
		callback();
	});
}
function scrollChatListEvent(action){
	if (action == 'up'){
		squadrapp.nav.loadOldTalkers(function(){
				$('.loader-chat-list').remove();
				talkers = squadrapp.nav.getTalkers();
				$.each(talkers.oldTalkers, function( index, value ) {
					if (value != undefined && value != null){
						if (!$('#talker-'+value.id_user).length){
							$("#list-chats").append('<section onClick="goChatEvent('+value.id_user+');" class="talker" id="talker-'+value.id_user+'"><div class="image user-image-60x60"><img width="60" height="60" src="https://graph.facebook.com/'+value.Facebook_id+'/picture?width=60&height=60"></div><section class="content"><h3>'+truncate(value.use_name,15,"")+'</h3><p>'+truncate(value.message)+'</p></section><section class="date">'+formatChatDate(value.date)+'</section><div class="corte"> </div></section>');
						}
					}
				});
				$('img').load(function() {
					$(this).show(); //muestra el div despues de que la imagen carga.
				});
				scrollChatList.refresh();
			});
		$('.loader-chat-list').remove();
		scrollChatList.refresh();
	}
	if (action == 'down'){
		$('.loader-chat-list').remove();
		scrollChatList.refresh();
	}
}
//	Evento al hacer scroll al chat
	function scrollChatList(){
		scrollChatListEvent('off');
		if ( ($('.scrollbar_chat_section .track').height()) <= ($(".scrollbar_chat_section .thumb").position().top+$(".scrollbar_chat_section .thumb").height()+10) ){
			$("#loader-chat-list").show();
			$('.scrollbar_chat_section').tinyscrollbar_update('relative');
			squadrapp.nav.loadOldTalkers(function(){
				talkers = squadrapp.nav.getTalkers();
				$.each(talkers.oldTalkers, function( index, value ) {
					if (value != undefined && value != null){
						if (!$('#talker-'+value.id_user).length){
							$("#list-chats").append('<section onClick="goChatEvent('+value.id_user+');" class="talker" id="talker-'+value.id_user+'"><div class="image user-image-60x60"><img width="60" height="60" src="https://graph.facebook.com/'+value.Facebook_id+'/picture?width=60&height=60"></div><section class="content"><h3>'+truncate(value.use_name,15,"")+'</h3><p>'+truncate(value.message)+'</p></section><section class="date">'+formatChatDate(value.date)+'</section><div class="corte"> </div></section>');
						}
					}
				});
				$('img').load(function() {
					$(this).show(); //muestra el div despues de que la imagen carga.
				});
				scrollChatListEvent('on');
				$("#loader-chat-list").hide();
				$('.scrollbar_chat_section').tinyscrollbar_update('bottom');
			});
		 }else{
		 	scrollChatListEvent('on');
		 }
	}
//	Evento al hacer scroll al chat
	function getNewChatList(){
		squadrapp.nav.loadNewTalkers(function(){
			talkers = squadrapp.nav.getTalkers();
			$.each(talkers.newTalkers, function( index, value ) {
				if (value != undefined && value != null){
					if ($('#talker-'+value.id_user).length){
						$('#talker-'+value.id_user).remove();
					}
					$("#list-chats").prepend ('<section onClick="goChatEvent('+value.id_user+');" class="talker" id="talker-'+value.id_user+'"><div class="image user-image-60x60"><img width="60" height="60" src="https://graph.facebook.com/'+value.Facebook_id+'/picture?width=60&height=60"></div><section class="content"><h3>'+truncate(value.use_name,15,"")+'</h3><p>'+truncate(value.message)+'</p></section><section class="date">'+formatChatDate(value.date)+'</section><div class="corte"> </div></section>');
				}
			});
			$('img').load(function() {
				$(this).show(); //muestra el div despues de que la imagen carga.
			});
			scrollChatList.refresh();
			autoLoadChatList('start');
		});
	}

	function autoLoadChatList(command){
		if(command == 'start'){
			timerChatList = setTimeout(function () {
				getNewChatList();
			}, 10000); 
		}else {
			timerChatList = null;
		}
	}



/*
 *	Evento click sobre usuarios en la lista de conversaciones 
 */
	function goChatEvent(user_id){
		mySwipe.next();
		$(".overlay_chat").show();
		talker = squadrapp.nav.getChatWithUser(user_id);
		$('#content-chatwith-section').html('');
		$('#content-chatwith-section').load('chat-with.html', function() {
			
			var pullDownEl = document.getElementById('pullDown-chatwith');
			var pullDownOffset = pullDownEl.offsetHeight;
			var pullUpEl = document.getElementById('pullUp-chatwith');	
			var pullUpOffset = pullUpEl.offsetHeight;
			
			$('#chat-with').height(($('#chat_section').height()));
			scrollChatWith = new iScroll('scroll-chatwith', {
				onScrollMove: function () {
					if (this.y > 5 && !pullDownEl.className.match('flip')) {
						pullDownEl.className = 'flip';
						this.minScrollY = 0;
					} else if (this.y < 5 && pullDownEl.className.match('flip')) {
						pullDownEl.className = '';
						this.minScrollY = -pullDownOffset;
					} else if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
						pullUpEl.className = 'flip';
						scrollChatWith.refresh();
					} else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
						pullUpEl.className = '';
						scrollChatWith.refresh();
					}
				},
				onScrollEnd: function () {
					if (pullDownEl.className.match('flip')) {
						$('.loader-chatwith').remove();
						pullDownEl.className = '';
						$('#list-messages').prepend('<div class="loader-chatwith" align="center"><img src="img/loader.gif" style="display: inherit;" /></div>');
						scrollChatWithEvent('down');
					} else if (pullUpEl.className.match('flip')) {
						$('.loader-chatwith').remove();
						pullUpEl.className = 'flip';
						$('#list-chats').append('<div class="loader-chatwith" align="center"><img src="img/loader.gif" style="display: inherit;" /></div>');
					    //scrollChatList.refresh();
						scrollChatWithEvent('up');
					}
				}
			});
			
			$( "#chat-with" ).attr( "user_id",  user_id);
			$('#chat-with .header .title').html(truncate(talker.use_name,15,""));
			$("#chat-with .header .chat_icon").html('<img width="36" height="36" src="https://graph.facebook.com/'+talker.Facebook_id+'/picture?width=36&height=36">');
			$.each(talker.chat.messages, function( index, value ) {
				if (value != undefined && value != null){
					if (value.user_id == squadrapp.user.getUserId()){
						$("#list-messages").prepend ('<section class="me"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
					}else{
						$("#list-messages").prepend ('<section class="friend"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
					}
				}
			});
			loadImages();
			$("#list-messages").append('<section class="vacio">&nbsp;</section>');
			scrollChatWith.refresh();
			scrollChatWith.scrollTo(0, -$('#list-messages').height(), 200);
			scrollChatWithEvent('on');
			autoLoadNewMessages('start');
		});
	}


function scrollChatWithEvent(action){
	if (action == 'up'){
		$('.loader-chatwith').remove();
		scrollChatWith.refresh();
	}
	if (action == 'down'){
		$('.loader-chat-list').remove();
		squadrapp.nav.loadOldMessagesByUser($( "#chat-with" ).attr( "user_id"), function(){
			talker = squadrapp.nav.getChatWithUser($( "#chat-with" ).attr( "user_id"));
				$.each(talker.chat.olders, function( index, value ) {
					if (value != undefined && value != null){
						if (value.user_id == squadrapp.user.getUserId()){
							$("#list-messages").prepend ('<section class="me"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
						}else{
							$("#list-messages").prepend ('<section class="friend"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
						}
					}
				});
				loadImages();
				$('.loader-chatwith').remove();
				scrollChatWith.refresh();
		});
	}
}
//	Evento al hacer scroll al chat
	function scrollChatWith(){
		scrollChatWithEvent('off');
		if ( 10 >= $(".scrollbar_chatwith_section .thumb").position().top ){
			getMoreMessages();
		 	scrollChatWithEvent('on');
		 }else{
		 	scrollChatWithEvent('on');
		 }
	}
	
function getMoreMessages(){
	$( "#loader-chat-with" ).show();
	squadrapp.nav.loadOldMessagesByUser($( "#chat-with" ).attr( "user_id"), function(){
		talker = squadrapp.nav.getChatWithUser($( "#chat-with" ).attr( "user_id"));
			$('#chat-with .header .title').html(truncate(talker.use_name,15,""));
			$("#chat-with .header .chat_icon").html('<img width="36" height="36" src="https://graph.facebook.com/'+talker.Facebook_id+'/picture?width=36&height=36">');
			$.each(talker.chat.messages, function( index, value ) {
				if (value != undefined && value != null){
					if (value.user_id == squadrapp.user.getUserId()){
						$("#list-messages").prepend ('<section class="me"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
					}else{
						$("#list-messages").prepend ('<section class="friend"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
					}
				}
			});
			loadImages();
			$( "#loader-chat-with" ).hide();
			scrollChatWithEvent('on');
	});
}
//	Evento al hacer scroll al chat
	function getNewMessages(){
		squadrapp.nav.loadNewMessagesByUser($( "#chat-with" ).attr( "user_id"), function(){
			var messages = squadrapp.nav.getNewMessagesByUser($( "#chat-with" ).attr( "user_id"));
				if (messages.length){
					$(".vacio").remove();
					for (var i = messages.length; i>0; i--){
						var value = messages[i-1];
						if (value.user_id == squadrapp.user.getUserId()){
							$("#list-messages").append ('<section class="me"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
						}else{
							$("#list-messages").append ('<section class="friend"><div class="date">'+formatChatDate(value.date)+'</div><div class="message"><div class="user">'+truncate(value.user_name,15,"...")+'</div><div class="text">'+value.message+'</div></section>');
						}
						scrollChatWith.refresh();
						scrollChatWith.scrollTo(0, -$('#list-messages').height(), 200);
					}
					$("#list-messages").append('<section class="vacio">&nbsp;</section>');
					scrollChatWith.refresh();
				}
				loadImages();
				autoLoadNewMessages('start');
		});
	}
	
	function autoLoadNewMessages(command){
		if(command == 'start'){
			setTimeout(function () {
				getNewMessages();
			}, 10000); 
		}
	}





function loadImages(){
	$('img').load(function() {
		$(this).show(); //muestra el div despues de que la imagen carga.
	});
}

function loadPage(page){
	$('#pages').load(page, function() {
		snapper.close();
	});
}






















/*
 * Utilidades
 */
function truncate(cadena, size, pos){
	size = size || 20;
	pos = pos || '...';
	if (cadena.length <= size){ pos = ''; }
	return jQuery.trim(cadena).substring(0, size).trim(this) + pos;
}


function formatChatDate(fecha){
			fecha = fecha || '';
			if (jQuery.trim(fecha)!=''){
				var today = new Date();
				var dateTimeSplit = jQuery.trim(fecha).split(' ');
	            var dateSplit = dateTimeSplit[0].split('-');
	            var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0].substring(2, 4);
	            //currentDate is 18/10/10
	            var currentTime = dateTimeSplit[1].substring(0, 5);
	            //currentTime is 10:06
				if ((dateSplit[2]+'/'+dateSplit[1]+'/'+dateSplit[0]) == (("0" + today.getDate()).slice(-2)+'/'+("0" + (today.getMonth() + 1)).slice(-2)+'/'+today.getFullYear())){
					return currentTime;
				}else {
					return currentDate;
				}
			}else{
				return '';
			}
}
