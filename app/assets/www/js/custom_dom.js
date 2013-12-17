(function(){
	var stage_width = $(document).width();
	$('#menu_icon').click(function(){
		$('#menu').panel( "toggle" );
	})
	$("#chat_icon").css('left', ((stage_width/2)-25));
})();

function getMenu(){
	$('#menu').load("menu.html");
}