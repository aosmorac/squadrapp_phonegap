<?php

class Ground_Model_DbTable_Field extends Zend_Db_Table_Abstract {

    protected $_name = "field";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getAllFields(){
         $select = $this->select()->where("active=1");
        return $this->fetchAll($select);
    }
    
    public function getAllFieldById($fie_id){
         $select = $this->select()->where("id_fie={$fie_id}");
        return $this->fetchAll($select);
    }
	
	public function getAllFieldByBranch($bra_id){
		$select = $this->select()->where("bra_id={$bra_id}");
        return $this->fetchAll($select);
	}
    
    
    
    
    public function getFieldsByBranch($idBranch){
        $select = $this->select()
            ->from(array('FIELD'=>'field'),array('field_id'=>'id_fie', 'sport_id'=>'spo_id'))
            ->setIntegrityCheck(false)
            ->join(array('SPORT' => 'sport'), "SPORT.id_spo = FIELD.spo_id "
                 , array('sport'=>'spo_name'))
            ->where("FIELD.active=1 AND FIELD.bra_id = {$idBranch}");
        $fields_base = $this->fetchAll($select)->toArray();
        
        $fields = array();
        
        foreach ($fields_base as $field) {
            
            if ( !isset( $fields[$field['sport_id']] ) ) {
                $fields[$field['sport_id']] = array();
                $fields[$field['sport_id']]['sport'] = $field['sport'];
                $fields[$field['sport_id']]['fields'] = array();
            }
            if ( !isset(  $fields[$field['sport_id']]['fields'][$field['field_id']] ) ) {
                $fields[$field['sport_id']]['fields'][$field['field_id']] = array();
            }
            if ( !isset(  $fields[$field['sport_id']]['fields'][$field['field_id']]['atributes'] ) ) {
                $fields[$field['sport_id']]['fields'][$field['field_id']]['atributes'] = array();
            }
            
            $select_att = $this->select()
                            ->from(array('FEATURE'=>'fieldxattribute'),array('feature_description'=>'description'))
                            ->setIntegrityCheck(false)
                            ->join(array('ATTRIBUTE' => 'field_attribute'), "ATTRIBUTE.id_fie_att = FEATURE.fie_att_id "
                                 , array('attribute'=>'fie_att_name'))
                            ->where("FEATURE.fie_id = {$field['field_id']}")
                            ->order('ATTRIBUTE.father_id ASC');
            $fields[$field['sport_id']]['fields'][$field['field_id']]['atributes'] = $this->fetchAll($select_att)->toArray();
        }
        
        //Zend_Debug::dump($fields_base, 'BASE');
        //Zend_Debug::dump($fields, 'TODO');
    }


}

