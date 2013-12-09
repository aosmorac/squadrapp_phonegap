<?php

class Model_Suggestions {

    
    public function __construct() {
    }
    
    
    /**
     * 
     * @param type $other_city
     */
    public function setCity($other_city){
        $city = new Model_DbTable_WishCity();
        $data = array('use_id'=> App_User::getUserId(), 'wis_cit_name'=>$other_city);
        $city->insert($data);
    }    
    
    
    
    public function setSport($other_sport){
        $sport = new Model_DbTable_WishSport();
        $data = array('use_id'=> App_User::getUserId(), 'wis_spo_name'=>$other_sport);
        $sport->insert($data);
    } 
    
    

    
    
}