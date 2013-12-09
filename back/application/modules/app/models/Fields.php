<?php

class App_Model_Fields {

    private $registroDataTable;

    
    public function __construct() {
        $this->registroDataTable = new App_Model_DbTable_Branch();
    }
    
    
	/**
	 * Construir el jason completo para places por deporte y ciudad
	 * 
	 * 
	 */
	public function getPlacesByCity($cid = 2257, $sid = 1){
		$places = array( 'list' => array(), 'filter' => array() );
		$places_temp = $this->registroDataTable->getPlacesByCity($cid, $sid);
		if ( count($places_temp) > 0 ){
			foreach ($places_temp AS $p) {
				$places['list'][$p['bra_id']] = $p;
				$places['filter'][] = $p['bra_id'];
			}
		}
		//Zend_Debug::dump($places); die;
		return $places;
	}


    
    
}