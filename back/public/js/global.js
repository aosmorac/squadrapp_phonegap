$(function() {
  //para colorear las filas de las tablas
  $(".zebra tr:odd").addClass("iceDatTblRow1");
  $(".zebra tr:even").addClass("iceDatTblRow2");
  $(".iceDatTbl tbody tr").hover(
	  function () {
	    $(this).addClass("resaltar_fila");
	  },
	  function () {
	    $(this).removeClass("resaltar_fila");
	  }
  );
  
  $("#confirmation_delete").dialog({
      autoOpen: false,
      modal: true,
      //show: 'slide',
      //hide: 'slide',
      width: '400'
  });

  //para hacer que todos los enlaces de borrados deban ser confirmados
  $("a.delete").click(function(e){
      e.preventDefault();
      var loc = document.location;
      var targetUrl = loc.protocol+"//"+loc.hostname+((loc.port!="")?":"+loc.port:"")+$(this).attr("href");
      //alert(targetUrl);
      $("#confirmation_delete_msg").text($(this).attr("rel"));
      $('#confirmation_delete').dialog('option', 'buttons', {
              "No eliminar" : function() {
                $(this).dialog("close");
              },
              "Si" : function() {
                window.location.href = targetUrl;
              }
      });
      $("#confirmation_delete").dialog("open");

  });
  
  //para colocar marcas a los labels de elementos de formularios requeridos
  $("label.required").prepend("*");
  
  // para colocar iconos a los enlaces de descarga
  $("a.file[href*='.doc/'], a.file[href*='.docx/'], a.file[href*='.rtf/']").addClass("doc");
  $("a.file[href*='.xls/'], a.file[href*='.xlsx/']").addClass("xls");
  $("a.file[href*='.ppt/'], a.file[href*='.pptx/']").addClass("ppt");
  $("a.file[href*='.pdf/']").addClass("pdf");
  $("a.file[href*='.zip/']").addClass("zip");
  $("a.file[href*='.rar/']").addClass("rar");
  $("a.file[href*='.bmp/']").addClass("bmp");
  $("a.file[href*='.gif/']").addClass("gif");
  $("a.file[href*='.jpg/']").addClass("jpg");
  $("a.file[href*='.png/']").addClass("png");

  //Para ocultar los labels vacios que se crean de los elementos de formularios ocultos
  $("dt[id$='-label']").each(function(index){
	  if($.trim($(this).html())=="&nbsp;") {
		  $(this).hide();
	  }
  });
  
});//fin del onready

function SoloNum(Nombreobjeto){
    $(Nombreobjeto).keypress(function(e){ 
        key=(document.all) ? e.keyCode : e.which;
        if (key > 45 && key < 58 || key == 37 || key == 39 || key == 0 || key == 9 || key == 8)
        {return true;}
        else 
        {return false;}
    });
 }