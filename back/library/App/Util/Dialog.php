<?php
class App_Util_Dialog {
	// Atributos del control dialog
	const AUTOOPEN = "autoOpen";
	const BUTTONS = "buttons";
	const CLOSEONESCAPE = "closeOnEscape";
	const DIALOGCLASS = "dialogClass";
	const DRAGGABLE = "draggable";
	const HEIGHT = "height";
	const MODAL = "modal";
	const POSITION = "position";
	const RESIZABLE = "resizable";
	const WIDTH = "width";
	const ZINDEX = "zIndex";

    /**
     * Los valores iniciales para  $_parts array.
     * @var array
     */
    protected static $_partsInit = array(
        self::AUTOOPEN     	=> "false",
        self::BUTTONS      	=> array("Cerrar"=>"$(this).dialog('close')"),
        self::CLOSEONESCAPE => "true",
        self::DIALOGCLASS   => "",
        self::DRAGGABLE     => "true",
        self::HEIGHT        => "auto",
        self::MODAL         => "true",
        self::POSITION      => "['center','top']",
        self::RESIZABLE     => "true",
        self::WIDTH         => 400,
        self::ZINDEX        => 1000
    );
	
        /**
     * Inicializar el $_partsInit en elconstructor.
     *
     * @var array
     */
	protected $_parts = array ();
	
	    /**
     * Array de los diferentes dialogos usados en el controlador.
     *
     * @var array
     */
	private $_dialogs;
	
	   /**
     * Class constructor
     */
	function __construct() {
		$this->_parts = self::$_partsInit;
		$this->_dialogs = array ();
	}
	
	    /**
	     *Agregar un Dialog en la pagina
	     *
	     * @param $titulo Titulo del dialog
	     * @param $url Ruta para cargar el contenido de una vista en el dialog
	     * @param $parametros Parametros necesarios para cargar la vista
	     */
	function addDialog($title, $url="", $params="") {
		$idControl = str_replace ( " ", "", $title );
		$this->_dialogs [$idControl] ["title"] = $title;
		$this->_dialogs [$idControl] ["url"] = $url;
		foreach($this->_parts as $attrib =>$value){
			$this->_dialogs [$idControl] [$attrib] = $value;
		}
		$this->_dialogs [$idControl] ["link"] = $url;
		$this->_dialogs [$idControl] ["params"]=$params;
		$this->_dialogs [$idControl] ["content"]="";
		//
	//		autoOpen //true False
	//		buttons //texto funcion
	//		closeOnEscape // true false
	//		dialogClass // ej. alert
	//		draggable // true false
	//		height
	//		hide //sline
	//		modal //true
	//		position // 'center', 'left', 'right', 'top', 'bottom',['right','top']
	//		resizable //
	//		stack // true el de mas arriba
	//		title
	//		width //300
	//		zIndex // 1000

	}
	
	/**
	 *Elimina un dialog
	 *
	 * @param $titulo  Titulo del dialog a eliminar
	 */
	function deleteDialog($title) {
		$idControl = str_replace ( " ", "", $title );
		unset ( $this->_dialogs [$idControl] );
	}
	
	/**
	 * Agregar un Link para armar la cadena de link necesaria pra cargar un dialog
	 * @param $titulo  Titulo del dialog
	 * @param $link mensaje del link a armar
	 */
	function linkDialog($title, $link) {
		$idControl = str_replace ( " ", "", $title );
		$this->_dialogs [$idControl] ["link"] = $link;
	}
	
	/**
	 * Agrergar o modificar el contenido de un dialo
	 * @param $titulo  Titulo del dialog
	 * @param $contenido Contenido del dialog
	 */
	function contentDialog($title, $content) {
		$idControl = str_replace ( " ", "", $title );
		$this->_dialogs [$idControl] ["content"] = $content;
	}
	
	/**
	 * Parametros requeridos por una vista a cargar en el dialog
	 * @param $titulo  Titulo del dialog
	 * @param $parametros Parametros del dialog ej. "/codigo/5"
	 */
	function paramsDialog($title, $params) {
		$idControl = str_replace ( " ", "", $title );
		$this->_dialogs [$idControl] ["params"] = $params;
	}
	
	/**
	 * Armar link <A> para cargar el dialog
	 * @param $titulo  Titulo del dialog
	 * @param $parametros Parametros necesarios para cargar la vista ej. "/codigo/5"
	 * @return HTML Codigo que muestra en la pagina el link
	 */
	function getLink($title,$params="")
	{
		$dlgHTML="";
		$idControl = str_replace ( " ", "", $title );
		if ("" != $this->_dialogs [$idControl]["link"]) {
			$dlgHTML .= "<a href=\"javascript:opendlg('{$idControl}','{$this->_dialogs [$idControl] ["url"]}/{$params}');\">{$title}</a>";
		}
		return $dlgHTML;
		
	}
	
	/**
	 * Retorna el array de Dialog
	 * @return Array Array de Dialogs
	 */
	function toArray() {
		return $this->_dialogs;
	}
	
	    /**
     * Definir si el dialog se abre automaticamente.
     *
     * @param $flag "true" para que el dialog se abra automaticamente, "false" para que no se abra
     */
	public function autoOpen($flag = "true") {
		$this->_parts [self::AUTOOPEN] =  $flag;
		return $this;
	}
	
	/**
	 *Definir botones que mostrará el dialog
	 * @param $flag Botones a mostrar Ej.  array("Aceptar"=>"$(this).dialog('close')")
	 */
	public function buttons($flag = array("Ok"=>"$(this).dialog('close')")) {
		$this->_parts [self::BUTTONS] = $flag;
		return $this;
	}
	
	/**
	 * Definir si el dialog se cierra al presionar la tecla ESC
	 * @param $flag
	 */
	public function closeOnEscape($flag = "true") {
		$this->_parts [self::CLOSEONESCAPE] =  $flag;
		return $this;
	}
	
	public function dialogClass($flag = '') {
		$this->_parts [self::DIALOGCLASS] =  $flag;
		return $this;
	}
	
	public function draggable($flag = "true") {
		$this->_parts [self::DRAGGABLE] =  $flag;
		return $this;
	}
	
	public function height($flag = "auto") {
		$this->_parts [self::HEIGHT] = $flag;
		return $this;
	}
	
	public function modal($flag = "true") {
		$this->_parts [self::MODAL] =  $flag;
		return $this;
	}
	
	public function position($flag = "") {
		$this->_parts [self::POSITION] = $flag;
		return $this;
	}
	
	public function resizable($flag = "false") {
		$this->_parts [self::RESIZABLE] = $flag;
		return $this;
	}
		
	public function width($flag = 300) {
		$this->_parts [self::WIDTH] = $flag;
		return $this;
	}
	
	public function zIndex($flag = 1000) {
		$this->_parts [self::ZINDEX] = $flag;
		return $this;
	}
	
	public function getPart($part) {
		$part = strtolower ( $part );
		return $this->_parts [$part];
	}
	
	function renderElement($nameDialog = "") {
		$arr_keys = array_keys ( $this->_dialogs );
		$dlgHTML = "";
		
		for($i = 0; $i < count ( $this->_dialogs ); $i ++) {
			$link = $this->_dialogs [$arr_keys [$i]] ["link"];
			$title = $this->_dialogs [$arr_keys [$i]] ["title"];
			$url = $this->_dialogs [$arr_keys [$i]] ["url"];
			$content = $this->_dialogs [$arr_keys [$i]] ["content"];
			$params = $this->_dialogs [$arr_keys [$i]] ["params"];
			$dlgHTML .= "<iframe id=\"{$arr_keys [$i]}\" title=\"{$title}\">\n";
			$dlgHTML .= "{$content}";
			$dlgHTML .= "</iframe>\n";
			$dlgHTML .= "<script type=\"text/javascript\">\n";
			$dlgHTML .= "$(document).ready(function(){\n";
			$dlgHTML .= "	var dialogOpts = {\n";
			$arrayExcepciones = array ("url", "link", "content", "params" );
			
			foreach ( $this->_dialogs [$arr_keys [$i]] as $attributes => $values ) {
				if ($attributes == "buttons") {
					$dlgHTML .= "		buttons: {\n";
					$indice=0;
					foreach ( $values as $nombre => $accion ) {
						$indice++;
						if($indice>1) $dlgHTML .= ",\n";
						$dlgHTML .= "			\"{$nombre}\": function() {\n";
						$dlgHTML .= "				{$accion};\n";
						$dlgHTML .= "			}";
					}
					$dlgHTML .= "		},\n";
				} elseif (($values != "") && ! (in_array ( $attributes, $arrayExcepciones ))) {
					$comillas = "";
					if ($attributes == "title" || $attributes == "height" || $attributes == "link" || $attributes == "url") {
						$comillas = "\"";
					}
					$dlgHTML .= "{$attributes}: {$comillas}{$values}{$comillas},\n";
				}
			}
			$dlgHTML .= "bgiframe: true";
			//cargar link dialog
			/*if ("" != $url) {
				$dlgHTML .= ",\n open: function() {\n";
				$dlgHTML .= "$(\"#{$arr_keys [$i]}\").load('{$url}{$parametros}');}";
			}*/
			$dlgHTML .= "\n};\n";
			$dlgHTML .= "$(\"#{$arr_keys [$i]}\").dialog(dialogOpts); \n";
			
			$dlgHTML .= "	     $('#lnkdgl_{$arr_keys [$i]}').click(\n";
			$dlgHTML .= "        function (){\n";
			$dlgHTML .= "            $(\"#{$arr_keys [$i]}\").dialog('open');\n";
			$dlgHTML .= "            return false;\n";
			$dlgHTML .= "        }\n";
			$dlgHTML .= "    );\n";
			
			$dlgHTML .= "});\n";
			
			$dlgHTML .= "function opendlg(dlgopen,url){\n
		var varWin = document.getElementById(dlgopen);\n
		$(varWin).load(url);
		$(varWin).dialog('open');
		}\n";
			$dlgHTML .= "</script>\n";
			if ("" != $link) {
				//$dlgHTML = "<a href=\"javascript:abrirdlg('{$arr_keys [$i]}','{$url}{$parametros}');\">{$link}</a>";
			}
			
		}
		return $dlgHTML;
	}
}

?>