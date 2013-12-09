<?php

class App_Model_DbTable_Branch extends Zend_Db_Table_Abstract {

    protected $_name = "branch";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    
	/**
	 * 
	 * 
	 * @param int $cid 
	 * @return object consulta
	 */
	public function getPlacesByCity($cid, $sid){
		$where = "CITY.ID = {$cid} AND FIELD.spo_id = {$sid} ";
		$select = $this->select()
                ->from(array('CITY' => 'city')
                        , array(
                            'city' => 'Name'
                          )
					)
                ->setIntegrityCheck(false)
                ->join(array('COUNTRY' => 'country')
                		, "COUNTRY.Code = CITY.CountryCode"
                        , array(
                        	'country' => 'Name'
						)
					)
                ->join(array('BRANCH' => 'branch')
                		, "BRANCH.city_ID = CITY.ID AND bra_active = 1"
                        , array(
                        	'bra_id' => 'id_bra'
                        	, 'com_id' => 'com_id'
                        	, 'name' => 'bra_name'
                        	, 'area' => 'bra_area'
                        	, 'neighborhood' => 'bra_neighborhood'
                        	, 'address' => 'bra_address'
                        	, 'phone' => 'bra_phone'
                        	, 'email' => 'bra_email'
                        	, 'location' => 'bra_location'
                        	, 'coordinates' => 'bra_coordinates'
                        	, 'lat' => 'bra_lat'
                        	, 'lng' => 'bra_lng'
                        	, 'alias' => 'bra_alias'
						)
					)
                ->join(array('COMPANY' => 'company')
                		, "COMPANY.id_com = BRANCH.com_id"
                        , array(
                        	'company' => 'com_name' 
						)
					)
                ->join(array('FIELD' => 'field')
                		, "FIELD.bra_id = BRANCH.id_bra"
                        , array(
                        	'spo_id' => 'spo_id' 
                        	, 'n_fields' => new Zend_Db_Expr('COUNT( FIELD.id_fie )')
						)
					)
			;//	Fin Select	---------------
		$select->where($where);//	Where	---------
        $select->group(new Zend_Db_Expr('
        				  COUNTRY.Name
						, CITY.Name
						, COMPANY.com_name
						, BRANCH.id_bra
						, BRANCH.com_id
						, BRANCH.bra_name
						, BRANCH.bra_area
						, BRANCH.bra_neighborhood
						, BRANCH.bra_address
						, BRANCH.bra_phone
						, BRANCH.bra_email
						, BRANCH.bra_location
						, BRANCH.bra_coordinates
						, BRANCH.bra_lat
						, BRANCH.bra_lng
						, BRANCH.bra_alias
						, FIELD.spo_id 
					')
			);	// Fin group	-------------
		//Zend_Debug::dump($select.''); die;
		$row = $this->fetchAll($select);
        $fields = $row->toArray();
		return $fields;
	}
    
    


}

