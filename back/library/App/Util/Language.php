<?php

class App_Util_Language {
	
	static function getTextLanguage($language="Global") {
            $language=strtolower($language);
            $languageDefault = App_User::getLanguage();
            //FIXME:mperez obtener el default por application ini
            if(empty($languageDefault)) $languageDefault = 'sp';
            
                $type = "global";
                $directory = "/langs/";
                if("global" != $language){
                    $type = "module";
                    $directory = "/modules/{$language}".$directory;
                }
                $textLanguage = new Zend_Config_Ini(APPLICATION_PATH . "{$directory}{$languageDefault}.ini", $type);  
                Zend_Registry::set("text{$language}Language{$languageDefault}",$textLanguage);
         
            return $textLanguage;

	}
	
	
}//fin de la clase