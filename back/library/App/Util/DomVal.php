<?php

class App_Util_DomVal {
	
	function __construct() {
	
	}
	
   /**
    * Devuelve el array asociativo de un tipo de dominio
    * @param string $typeDomVal
    * @return array
    */
    static public function getArrayDomVal($typeDomVal, $showTextSelect = true,$changeLanguage=true){
        $servicesDomainValue = new Manager_Model_DomainValue();
        $arrayDomVal = $servicesDomainValue->getAllElements();
        //Zend_Debug::dump($arrayDomVal);
        //die;
        $textGlobal = App_Util_Language::getTextLanguage();
        if(strpos($typeDomVal,"domVal")==0){
            $typeDomVal = str_replace("domVal_", "", $typeDomVal);
        }
        $subCadena = "domVal_".$typeDomVal."_";
        if($showTextSelect){
            $arrayReturn[""] = $textGlobal->domVal->select;
            if($showTextSelect===true)
            	$arrayReturn[""] .=  " " .$textGlobal->domVal->$typeDomVal->label;
        }
        foreach ($arrayDomVal as $key => $domVal) {
            if(strpos($domVal["domVal_name"],$subCadena)===0){
                $index= $domVal["domVal_id"];
                $subString = str_replace($subCadena, "", $domVal["domVal_name"]);
                $value=$subString;
                if($changeLanguage){
                    $value= $textGlobal->domVal->$typeDomVal->$subString;
                }
                $arrayReturn[$index]=$value;
            }
        }
        
        return $arrayReturn;
    }
   /**
    * Devuelve el array de un tipo de dominio
    * @param string $typeDomVal
    * @return array
    */
    static public function getDomValValues($typeDomVal){
        $servicesDomainValue = new Manager_Model_DomainValue();
        $arrayDomVal = $servicesDomainValue->getAllElements();
        $subCadena = "domVal_".$typeDomVal."_";
        $arrayReturn = array();
        foreach ($arrayDomVal as $key => $domVal) {
            if(strpos($domVal["domVal_name"],$subCadena)===0){
                $index= $domVal["domVal_id"];
                $subString = str_replace($subCadena, "", $domVal["domVal_name"]);
                $arrayReturn[$index]=$subString;
            }
        }
        return $arrayReturn;
    }

    
    static  public function getIdByName($name){
        $servicesDomainValue = new  Manager_Model_DomainValue();
        $arrayDomVal = $servicesDomainValue->getAllElements();
        foreach ($arrayDomVal as $key => $domVal) {
            if($domVal["domVal_name"]==$name){
                return $domVal["domVal_id"];
            }
        }
        return false;
    }
	
}//fin de la clase