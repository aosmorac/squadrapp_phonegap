<?php

class App_Perfil {

	private static $_instance = null;
	private $_menus = array();
	private $_permits = array();
	private $_menu_actual = null;
	private $_permit = array();
	private $_permitDefault = array();
	private $_father = array();
	private $_allowField=array();
	private $_adiDatField = array();
	private $_permitModule = array();
	private $_reportModule = array();
	private $_company = array();

	function __construct(array $arr_func) {
		$permits = array();
		foreach (App_Util_DomVal::getArrayDomVal("permitType", false, false) as $index => $value) {

			$this->_permitDefault[$value] = false;
		}

		foreach ($arr_func as $key => $arrSub) {
			$arr = (array)$arrSub;
			$idmenu = $arr["mod_id"];
			$nombre = $arr["mod_name"];
			$label = $arr["mod_initials"];
			//$descripcion=$arr["descripcion"];
			$idpadre = $arr["mod_fatherId"];
			$orden = $arr["mod_position"];
			$urlpagina = $arr["mod_path"];
			$parametros = $arr["mod_params"];
			$modelo = $arr["mod_model"];
			if (empty($parametros)) {
				$parametros = "menu/{$idmenu}";
			} else {
				$parametros = "menu/{$idmenu}/{$parametros}";
			}
			$menu = array("name" => $nombre,
            	"label" => $label,
                "url" => $urlpagina,
                "param" => $parametros,
                "children" => array());

			if (empty($idpadre)) {
				$this->_menus[$idmenu] = $menu;
				$this->_father[$idmenu] = $idmenu;
			} else {
				if (isset($this->_menus[$idpadre])) {
					$this->_menus[$idpadre]["children"][$idmenu] = $menu;
					$this->_father[$idmenu] = $idpadre;
				}
				foreach ($this->_menus as $subkey => $subvalue) {
					if (isset($subvalue[$idpadre])) {
						$this->_menus[$subkey]["children"][$idpadre]["children"][$idmenu] = $menu;
						$this->_father[$idmenu] = $subkey;
					}
				}
			}

			$nameValue = App_Util_DomVal::getValueDomVal($arr["domVal_permitType"], true);
			if (empty($urlpagina))
			$urlpagina = $idmenu;
			//$this->_permits[$idmenu][$urlpagina][]=$arr["domVal_permitType"];
			$this->_permits[$idmenu][$urlpagina][$nameValue] = true;
			$this->_permits[$idmenu]["model"]=$modelo;
		}
	}

	function setMenuActual() {
		return $this->_menu_actual;
	}
	public static function getMenuActual() {
		$loggedUser = new Zend_Session_Namespace("loggedUser");
		return  $loggedUser->MenuActual;
	}

	function getFather($idmenu) {
		$this->_menu_actual = $idmenu;
		if(isset($this->_father[$idmenu])) return $this->_father[$idmenu];
		return false;
	}
	
	function getReportModule()
	{
		$perfil = self::getInstance();
		$mod_id = self::getMenuActual();
		if(empty($mod_id)) return array();
		if(isset($perfil->_reportModule[$mod_id])) return $perfil->_reportModule[$mod_id];
		return array();
	}
        
        function getInitialRol(){
            $loggedUser = new Zend_Session_Namespace ( "loggedUser" );
	    return $loggedUser->rol_Initials;
        }
        
        function getPapersaveTypeModule($inLine = false)
	{
		$perfil = self::getInstance();
		$mod_id = self::getMenuActual();
		if(empty($mod_id)) return array();
                if ($inLine){
                    if(isset($perfil->_papersaveModule[$mod_id])){
                        $documentTypes = array();
                        $papersaveModules = $perfil->_papersaveModule[$mod_id];
                        foreach ($papersaveModules as $host) {
                            foreach ($host as $module) {
                                foreach ($module as $category) {
                                    foreach ($category as $k => $document) {
                                            $documentTypes[$k] = $document;
                                    }
                                }
                            }
                        }
                        return $documentTypes;
                    }
                }else{ 
                    if(isset($perfil->_papersaveModule[$mod_id])) return $perfil->_papersaveModule[$mod_id];
                }
                return array();
	}
        
        function getPapersaveTypeModuleString($separator = ','){
            $typesArray = self::getPapersaveTypeModule(true);
            $typesArray = array_keys($typesArray);
            if (count($typesArray)>0){
                $types = implode($separator, $typesArray);
                return $types;
            }else {
                return false;
            }
        }

	function getMenus() {
		return $this->_menus;
	}

	public static function load($idMenu="",$mvc="") {
		$perfil = self::getInstance();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		if(empty($mvc)) $mvc = $request->getModuleName() . "/" . $request->getControllerName() . "/" . $request->getActionName();
		if(empty($idMenu)){
			$loggedUser = new Zend_Session_Namespace("loggedUser");
			$idMenu = $loggedUser->MenuActual;
		}
		$perfil->_permit = $perfil->_permitDefault;
		if (substr($mvc, 0, 1) == "/")
		$mvc = substr($mvc, 1, strlen($mvc) - 1);
		$n = strpos($mvc, '/', strpos($mvc, '/', strpos($mvc, '/') + 1) + 1);
		if ($n > 0) {
			$mvc = substr($mvc, 0, $n);
		}
		$perfil->_menu_actual = $idMenu;
		//echo " Menu: {$idMenu} MVC: {$mvc} <br/>";
		if (!isset($perfil->_permits[$idMenu][$mvc]) && defined("SECURITY_MODE_ALL") && SECURITY_MODE_ALL == true) {
			foreach ($perfil->_permitDefault as $index => $value) {
				$perfil->_permit[$index] = true;
			}
			return self::$_instance;
		}

		if (!isset($perfil->_permits[$idMenu][$mvc])) {
			return false;
		}

		foreach ($perfil->_permitDefault as $index => $value) {
			if (isset($perfil->_permits[$idMenu][$mvc][$index])) {
				$perfil->_permit[$index] = true;
			}
		}
		return self::$_instance;
	}

	/**
	 * Singleton pattern implementation makes "clone" unavailable
	 * @return void
	 */
	protected function __clone() {

	}

	/**
	 * Retorna la instancia de App_Perfil almacenada en la sesion del usuario
	 * Singleton pattern implementation
	 *
	 * @return App_Perfil
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			$loggedUser = new Zend_Session_Namespace("loggedUser");
			if (empty($loggedUser)) {
				return false;
			}
			$perfil = unserialize($loggedUser->perfil);
			if (is_object($perfil)) { //TODO tal vez mejor un instance of App_Perfil
				self::$_instance = $perfil;
			}
		}
		return self::$_instance; /* @var self::$_instance App_Perfil */
	}

	function getPermit($value="") {
		if (empty($value))
		return $this->_permit;
		if (!isset($this->_permit[$value]))
		return false;
		return $this->_permit[$value];

		//    return $this->_permit[$value]["value"];
	}

	/**
	 * Obtener las columnas permitidas al usuario Logueado
	 * @param int $typeColumn 1 - Solo datos de la tabla, 2 - SOlo datos Adicionales, 3 Ambos
	 * @param array $columns Filtras determinadas Columnas o retornar todas
	 * @return Array Array de las columnas filtradas
	 */
	static public function getPermitsColums($typeColumn = 3,$inLine = false,$columns=null,$childrenAditionData=1){
		$perfil = self::getInstance();
		$arrayColumnsField=array();
		$arrayAditionalData = array();
		$mod_id = self::getMenuActual();
		$user_id = App_User::getUserId();
		if(empty($mod_id)) return array();
		if($typeColumn!=2){
			//FIXME: activar el caché
			//$cache = Zend_Registry::get("cache");
			//$nameCache="GetCol_{$user_id}_{$mod_id}";
			//if (!$arrayColumnsField = $cache->load($nameCache)) {
			$modelo = (isset($perfil->_permits[$mod_id]))?$perfil->_permits[$mod_id]["model"]:"";
			if(!empty($modelo)){
				$servicesModel= new $modelo;
				$columnTotal = $servicesModel->getColumns($servicesModel->getDataSelect());
				$columns = $perfil->diffColumns($columnTotal,$mod_id);
				$arrayColumnsField=App_Util_Array::arrayColumns($columns,$perfil->_allowField[$mod_id]);
			} else {
				$arrayColumnsField=array();
			}
			//	$cache->save($arrayColumnsField, $nameCache);
			// }
		}
		if($typeColumn!=1){
			$RMAdiDatModuleCompany = new Manager_Model_AdiDatModuleCompany();
			$adiDatModuleCompany = $RMAdiDatModuleCompany->getAdiDatModuleCompany($mod_id, null);
			$modelAditionalData = new Settings_Model_AditionalData();
			$adiDatModule=array();
			if(!empty($adiDatModuleCompany))
			{
				foreach($adiDatModuleCompany as $adiDat) {
					$adiDatModule[] = $adiDat->domVal_adiDat;
				}
				$aditionalDataTotal = $modelAditionalData->getAllGroup(null,null, $adiDatModule, "GB_MAIN_SIA");
				$aditionalData = $perfil->diffAdiDatColumns($aditionalDataTotal,$mod_id);
                                $c=0;
				$arrayAditionalData = App_Util_Array::arrayAditionalData($aditionalData,0,$perfil->_adiDatField[$mod_id],$c,$childrenAditionData);
				if($inLine){
                                    //Zend_Debug::dump($arrayAditionalData);echo $childrenAditionData;
					$arrayAditionalData= App_Util_Array::arrayAditionalDataInLine($arrayAditionalData,$childrenAditionData);
				}
			}
		}

		return array_merge($arrayColumnsField,$arrayAditionalData);//FIXME: quitame de aca, no hago nada
	}

	private function _getSpecialColumns($idmenu) {
		return $this->_columns[$idmenu];
	}
	public static function  getAditionalDataProfile(){
		//Instancia del modelo de Datos Adicionales por Modelo por Compañia
		$RMAdiDatModuleCompany = new Manager_Model_AdiDatModuleCompany();
			
		//instancia del modelo settings aditionalData
		$SettingsModelAdiDat = new Settings_Model_AditionalData();
		$perfil = self::getInstance();
		$mod_id = $perfil->getMenuActual();
			
			
		//datos adicionales generales
		$additionalDataSettings = "";
			
		//datos adicionales del modulo
		$adiDatModuleCompany = $RMAdiDatModuleCompany->getAdiDatModuleCompany($mod_id, null);
			
		//instancia del modelo settings aditionalData
		$SettingsModelAdiDat = new Settings_Model_AditionalData();
			
		//valido si el modulo tiene asignados datos adicionales de compañia
		if(!empty($adiDatModuleCompany))
		{
			//arma el array de id's de datos adicionales del modulo
			foreach($adiDatModuleCompany as $adiDat) {
				//FIXME: modificar para usar domVal y no id en datos adicionales
				//$adiDatModule[] = $adiDat->domVal_adiDat;
				$adiDatModule[] = $adiDat->adiDatModCom_id;
				
			}


			//Datos adicionales generales de compañia, asignado a un modulo
			$arrayDatosAdicionales = $SettingsModelAdiDat->getAllGroup(null,null, $adiDatModule, "GB_MAIN_SIA");
		}
		return $arrayDatosAdicionales;
	}

	function addParameter($name,$value){
		$param = "_".$name;
		$this->$param=$value;
	}

	private function diffColumns($columnTotal,$menuActual=null){
		$cont=0;
		$menuActual=(is_null($menuActual))?$this->getMenuActual():$menuActual;
		$arrayColumns = array();
		$columnPermits = (isset($this->_allowField[$menuActual])?$this->_allowField[$menuActual]:array());
		if(is_array($columnPermits)){
			foreach ($columnTotal as $key => $column) {
				$cont++;
				if(array_key_exists($column["COLUMN_NAME"], $columnPermits)){
					$arrayColumns[$column["COLUMN_NAME"]]=$column;
					$arrayColumns[$column["COLUMN_NAME"]]["visible"]=$columnPermits[$column["COLUMN_NAME"]]->visible;
				}
				if($cont==1 && count($arrayColumns)==0){
					$arrayColumns[$column["COLUMN_NAME"]]=$column;
					$arrayColumns[$column["COLUMN_NAME"]]["visible"]=false;
				}
			}
		}
		return $arrayColumns;
	}

	private function diffAdiDatColumns($aditionalDataTotal,$menuActual=null){
		$aditionalDataTotal_key=array();
		$arrayFathersPending=array();
		foreach ($aditionalDataTotal as $value) {
			$aditionalDataTotal_key[$value->adiDat_id]=$value;
		}
		$menuActual=(is_null($menuActual))?$this->getMenuActual():$menuActual;
		$arrayColumns = array();
		$aditionalDataPermits = (isset($this->_adiDatField[$menuActual])?$this->_adiDatField[$menuActual]:array());

		if(is_array($aditionalDataPermits)){
			//buscar Padres Pendientes
			foreach ($aditionalDataPermits as $aditionalData) {
				if(array_key_exists($aditionalData->adiDat_id, $aditionalDataTotal_key)){
					if(!is_null($aditionalDataTotal_key[$aditionalData->adiDat_id]->adiDat_father)){
						$keyFather = $aditionalDataTotal_key[$aditionalData->adiDat_id]->adiDat_father;
						$arrayFathersPending[$keyFather]=$keyFather;
						$searhFather = true;
						while($searhFather){
							$keyFather = $aditionalDataTotal_key[$keyFather]->adiDat_father;
							if(!is_null($keyFather)){
								$arrayFathersPending[$keyFather]=$keyFather;
							}else{
								$searhFather=false;
							}
						}
					}
				}
			}
				
			//agregar padres
			foreach ($arrayFathersPending as $keyFather) {
				$arrayColumns[$keyFather]=$aditionalDataTotal_key[$keyFather];
				$arrayColumns[$keyFather]->visible=false;
			}
				
			foreach ($aditionalDataPermits as $aditionalData) {
				if(array_key_exists($aditionalData->adiDat_id, $aditionalDataTotal_key)){
					$arrayColumns[$aditionalData->adiDat_id]=$aditionalDataTotal_key[$aditionalData->adiDat_id];
					$arrayColumns[$aditionalData->adiDat_id]->visible=$aditionalData->visible;
				}
			}
		}
		return $arrayColumns;
	}

	static public function getSpecialModules($initial=false){
		$perfil = self::getInstance();
		$arrayModules=array();
		$mod_id = self::getMenuActual();
		if(empty($mod_id)) return array();
		$columns = $perfil->_permitModule;
		$permits = App_Util_DomVal::getArrayDomVal("permitType", false, false);
		$defaultPermit=array();
		foreach ($permits as $key => $value) {
			$arrayPermits[$key] = $value;
			$defaultPermit[$value]=false;
		}
		$posInitial="";
		if(isset($columns["permits"][$mod_id])){
			foreach ($columns["permits"][$mod_id] as $key => $permits) {
				$arrayColumnPermits=$defaultPermit;
				foreach ($arrayPermits as $keyPermit => $value) {
					$keySearch = ",".$keyPermit.",";
					if( strpos( ",".$permits,$keySearch)!==false ){
						$arrayColumnPermits[$value]=true;
					}
				}
				$arrayModules[$key]=$columns["modules"][$key];
				$arrayModules[$key]["permits"]=$arrayColumnPermits;
				if($initial){
					if($columns["modules"][$key]["mod_initials"]==$initial){
						$posInitial=$key;
					}
				}
			}
		}
		if($initial){
			return (isset($arrayModules[$posInitial]))?$arrayModules[$posInitial]:false;
		}
                //Zend_Debug::dump($arrayModules);
		return $arrayModules;
	}

	static public function isSpecialModule($MVC){
		$perfil = self::getInstance();
		if(!$perfil) return false;
		$mod_id = self::getMenuActual();
		$columns = $perfil->_permitModule;
		if(isset($columns["permits"])&&isset($columns["permits"][$mod_id])){
			foreach ($columns["permits"][$mod_id] as $key => $permits) {
				$textGlobal="/".$columns["modules"][$key]["url"]."/";
				$textSearch="/".$MVC."/";
				if( strpos( $textGlobal,$textSearch)!==false ){
					return true;
				}
			}
		}
		return false;
	}

	static public function getCompany($field=null){
		$perfil = self::getInstance();
		$company = $perfil->_company;
		if(is_null($field)) return $company;
		return $perfil->_company[$field];
	}
}

//fin de la clase
