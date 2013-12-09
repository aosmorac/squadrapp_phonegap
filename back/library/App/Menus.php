<?php

class App_Menus {
	
	private static $_instance = null;
	
	private $menu_id = array();
	private $menu_cod = array();

	private $_ver = false;
	private $_crear = false;
	private $_modificar = false;
	private $_eliminar = false;
	private $_nombre = null;
	private $_descripcion = null;
	private $_url = null;
	//private $_url2 = null;
	
	private $_menu_actual = null;
	private $_padres = array();

	function __construct(array $arr_func) {
		$acciones = App_Util_DomVal::getDomValValues("permitType");
		foreach ($arr_func as $key => $arr) {
			foreach ($acciones as $key=>$value) {
				$this->menu_cod[$arr["codseccion"]][$value] = $arr[$value];
				$this->menu_id[$arr["id"]][$value]	    = $arr[$value];
			}
		}
	}
	
	function registrar_padres($arr_padres) {
		$this->_padres = $arr_padres;
	}
	function padres_cargados() {
		return $this->_padres;
	}

	function menu_actual() {
		$this->_menu_actual;
	}

	function menus_cargados_codigo() {
		return array_keys($this->menu_cod);
	}
	function menus_cargados_id() {
		return array_keys($this->menu_id);
	}

	function cargar($id) {
	    
		if(!isset($this->menu_id[$id]) && !isset($this->menu_cod[$id]) && defined("SECURITY_MODE_ALL") && SECURITY_MODE_ALL==true) {
			$this->_ver 		= true;
			$this->_crear 		= true;
			$this->_modificar 	= true;
			$this->_eliminar 	= true;
			$this->_nombre 	= null;
			$this->_url 	= null;
			//$this->_url2 	= null;
			return true;
		}
		
		if(!isset($this->menu_cod[$id]) && !isset($this->menu_id[$id])) {
			$this->_ver 		= false;
			$this->_crear 		= false;
			$this->_modificar 	= false;
			$this->_eliminar 	= false;
			$this->_nombre 	= null;
			$this->_url 	= null;
			//$this->_url2 	= null;
			return false;
		}

		if(isset($this->menu_id[$id])) {
			//$this->_ver 		= ($this->menu_id[$id]["ver"]=="S")?true:false;
			//$this->_crear 		= ($this->menu_id[$id]["crear"]=="S")?true:false;
			//$this->_modificar 	= ($this->menu_id[$id]["modificar"]=="S")?true:false;
			//$this->_eliminar 	= ($this->menu_id[$id]["eliminar"]=="S")?true:false;
			$this->_nombre		= $this->menu_id[$id]["nombre"];
			$this->_url		 	= $this->menu_id[$id]["url"];
			//$this->_url2		= $this->menu_id[$id]["url2"];
		}
		elseif (isset($this->menu_cod[$id])) {
			$this->_ver 		= ($this->menu_cod[$id]["ver"]=="S")?true:false;
			$this->_crear 		= ($this->menu_cod[$id]["crear"]=="S")?true:false;
			$this->_modificar 	= ($this->menu_cod[$id]["modificar"]=="S")?true:false;
			$this->_eliminar 	= ($this->menu_cod[$id]["eliminar"]=="S")?true:false;
			$this->_nombre		= $this->menu_cod[$id]["nombre"];
			$this->_url		 	= $this->menu_cod[$id]["urlpagina"];
			$this->_descripcion	= $this->menu_cod[$id]["descripcion"];
			//$this->_url2	 	= $this->menu_cod[$id]["url2"];
		}
		$this->_menu_actual =  $id;
		return true;
	}
	
	/**
     * Singleton pattern implementation makes "clone" unavailable
     * @return void
     */
    protected function __clone() {}
    
	/**
     * Retorna la instancia de App_menu almacenada en la sesion del usuario
     * Singleton pattern implementation
     *
     * @return App_menu
     */
    public static function getInstance() {
        if (null === self::$_instance) {
        	$loggedUser = new Zend_Session_Namespace("loggedUser");
        	$menus = $loggedUser->menus;
        	if (isset($menus)) { //TODO tal vez mejor un instance of App_menu
        		//$padres = $this->separarpadres;
        		self::$_instance = $menus;
        	}
        }
        return self::$_instance; /* @var self::$_instance App_menu */
    }

	function ver() {
		return $this->_ver;
	}

	function crear() {
		return $this->_crear;
	}

	function modificar() {
		return $this->_modificar;
	}

	function eliminar() {
		return $this->_eliminar;
	}
	
	function nombre() {
		return $this->_nombre;
	}
	
	function url() {
		return $this->_url;
	}
	
	function descripcion() {
		return $this->_descripcion;
	}
	
	/*function url2() {
		return $this->_url2;
	}*/

}//fin de la clase
