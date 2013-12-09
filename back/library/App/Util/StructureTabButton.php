<?php

class App_Util_StructureTabButton {
	
	private $_tabs;
	private $_buttonVertical;
	private $_buttonHorizontal;
	private $_label;
	private $_labelSecundary;
	private $_nameTabs;
	private $_nameButtonH;
	private $_nameButtonV;
	private $_params;
	
	function __construct($structure,$url,$params=array()) {
		$this->_tabs = new App_Util_Tabs ( );
		$this->_buttonVertical = array();
		$this->_buttonHorizontal = array();
		$this->_label = array();
		$this->_labelSecundary = array();
		$this->_params = $params;
		$this->StructureTabButton($structure,$url);
	}
	
	function getTabs() {
		return $this->_tabs;
	}

    function getButtonVertical($code) {
		return $this->_buttonVertical[$code];
	}
	
    function getButtonHorizontal($code) {
		return $this->_buttonHorizontal[$code];
	}
    
	function StructureTabButton($structure,$url){
		//$baseUrl = Zend_Controller_Front::getInstance ()->getBaseUrl();
		$arr_keys = array_keys ( $structure);
        $actualFather="-1";
        $codeFather="";
        $subcodeFather="";
        $endSubFather ="-1";
		for($i = 0; $i < count ( $structure ); $i ++) {
		    $codvalordominio=$structure [$arr_keys [$i]] ["codvalordominio"];
			$codpadre = $structure [$arr_keys [$i]] ["codpadre"];
			$codigo = $structure [$arr_keys [$i]] ["codigo"];
			$nombre = $structure[$arr_keys [$i]] ["nombre"];
			$activo =$structure [$arr_keys [$i]] ["activo"];
			$label = $structure [$arr_keys [$i]] ["label"];
			$labelsintesis = $structure [$arr_keys [$i]] ["labelsintesis"];
			$completo = false;
			if(isset($structure [$arr_keys [$i]] ["completo"])) {
			    $completo = $structure [$arr_keys [$i]] ["completo"];
			}
			
			if( empty($codpadre) ){
			    $params=App_Util_SafeUrl::encriptar(array("codtab"=>$codigo),true);
			    if(!empty($nombre)){
				    $this->_tabs->addView ( $nombre,$url,$params );
				    if ($completo) {
				    	$this->_tabs->checker($nombre);
				    }
			    }
				$actualFather=$codvalordominio;
				$codeFather=$codigo;
				$this->_buttonHorizontal[$codeFather] = new App_Util_Botones() ;
				$this->_nameTabs[$codigo]=$nombre;
			    $this->_label[$codeFather.".1"]=$label;
	            $this->_labelSecundary[$codeFather.".1"]=$labelsintesis;
	            $this->_nameButtonH[$codeFather.".1"]=$nombre;
			}
			elseif($codpadre == $actualFather){
			    $params=App_Util_SafeUrl::encriptar(array("codtab"=>$codeFather,"codboton"=>$codigo),true);
			    if(!empty($nombre)){
	                $this->_buttonHorizontal[$codeFather]->agregar($nombre,$url,$params);
	                if ($completo) {
	                    $this->_buttonHorizontal[$codeFather]->checkear($nombre);
	                }
			    }
	            $endSubFather=$codvalordominio;
	            $subcodeFather=$codigo;
	            $this->_buttonVertical[$subcodeFather] = new App_Util_Botones() ;
	            $this->_label[$codeFather.".".$codigo]=$label;
	            $this->_labelSecundary[$codeFather.".".$codigo]=$labelsintesis;
	            $this->_nameButtonH[$codeFather.".".$codigo]=$nombre;
			}
			else{
			    $params=App_Util_SafeUrl::encriptar(array("codtab"=>$codeFather,"codboton"=>$subcodeFather,"codboton2"=>$codigo),true);
			    if(!empty($nombre)){
	                $this->_buttonVertical[$subcodeFather]->agregar($nombre,$url,$params);
			        if ($completo) {
	                    $this->_buttonVertical[$subcodeFather]->checkear($nombre);
	                }
			    }
	            $this->_label[$codeFather.".".$subcodeFather.".".$codigo]=$label;
	            $this->_labelSecundary[$codeFather.".".$subcodeFather.".".$codigo]=$labelsintesis;
	            $this->_nameButtonV[$codeFather.".".$subcodeFather.".".$codigo]=$nombre;
			}
		}
		if(count($this->_params)>0){
	    	$sesionTabBoton = new Zend_Session_Namespace("TABBOTON");
	    	$sesionTabBoton->opcion=$this->_params["codtab"]."_".$this->_params["codboton"]."_".$this->_params["codboton2"];
	    	$sesionTabBoton->NombreTab = $this->_nameTabs[$this->_params["codtab"]];
	    	$sesionTabBoton->NombreBotonH=$this->_nameButtonH[$this->_params["codtab"].".".$this->_params["codboton"]];
	    	$sesionTabBoton->NombreBotonV="";
	    	$codigo="";
	    	$titulo="xx";
	    	if(isset($this->_label[$this->_params["codtab"].".".$this->_params["codboton"].".".$this->_params["codboton2"]])) {
	    	    $sesionTabBoton->NombreBotonV=$this->_nameButtonV[$this->_params["codtab"].".".$this->_params["codboton"].".".$this->_params["codboton2"]];
	    	    $codigo=$this->_params["codtab"].".".$this->_params["codboton"].".".$this->_params["codboton2"];
	    	    $titulo=$sesionTabBoton->NombreBotonV;
	    	    $this->_buttonHorizontal[$this->_params["codtab"]]->actual($sesionTabBoton->NombreBotonH);
	    	    $this->_buttonVertical[$this->_params["codboton"]]->actual($sesionTabBoton->NombreBotonV);
	    	}
	    	else {
	    	    $codigo=$this->_params["codtab"].".".$this->_params["codboton"];
	    	    $titulo=$sesionTabBoton->NombreBotonH;
	    	    $this->_buttonHorizontal[$this->_params["codtab"]]->actual($sesionTabBoton->NombreBotonH);
	    	}
	    	$sesionTabBoton->label=$this->_label[$codigo];
	    	$sesionTabBoton->labelsintesis=$this->_labelSecundary[$codigo];
	    	$sesionTabBoton->titulo=$titulo;
	    	$this->_tabs->actual($sesionTabBoton->NombreTab);
		}
    }
}
?>