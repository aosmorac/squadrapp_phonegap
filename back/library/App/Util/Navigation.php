<?php

class App_Util_Navigation {
	
	private $_navs;
	function __construct() {
		$this->_navs=array();
		//FIXME: Etiqueta desde el idioma
		$this->addNavigation ( "Inicio", "#", "Página principal" );
	}
	
	function addNavigation($label,$linkPage="",$description="") {
		$i = count($this->_navs);
		$this->_navs[$i]["label"]=$label;
		$this->_navs[$i]["url"]=$linkPage;
		if(empty($description)) {
			$description = "Ir a {$label}";
		}
		$this->_navs[$i]["description"]=$description;
	}
	
	function editNavigation($label,$linkPage,$description="") {
		$cant = count($this->_navs);
		for ($i = 0; $i < $cant; $i++) {
			if($this->_navs[$i]["label"] == $label) {
			    $this->_navs[$i]["url"]=$linkPage;
        		if(empty($description)) {
        			$description = "Ir a {$label}";
        		}
        		$this->_navs[$i]["description"]=$description;
        		break;
			}
		}
	}
	
	function toArray(){
		return  $this->_navs;
	}
}

?>