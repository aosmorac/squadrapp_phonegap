<?php

class App_Util_AutoComplete {
	
	private static $autoComplete;
        private static $db_table;
        
	function __construct() {
            $this->autoComplete = null;
            $this->db_table = null;
	}
        
        
        public static function getCities($country, $cit){
            self::$db_table = new Model_DbTable_City();
            $values = self::$db_table->getCities($country, $cit);
            return $values->toArray(); 
        }
        
        public static function getSports($spo){
            self::$db_table = new Model_DbTable_Sport();
            $values = self::$db_table->getSports($spo);
            return $values->toArray(); 
        }
            
                    
	
	
}
