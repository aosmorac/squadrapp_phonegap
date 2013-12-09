<?php

class User_Model_DbTable_SportsUser extends Zend_Db_Table_Abstract {

    protected $_name = "sportsxuser";
    
    protected $_primary = 'sport_id';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getUserSports($uid, $all=1){
         if ($all == 1) {
            $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('S' => 'sport')
                        , array('sport_id'=>'id_spo', 'sport_name'=>'spo_name'))
                ->joinleft(array('SU' => 'sportsxuser'), "SU.sport_id = S.id_spo AND SU.user_id = {$uid}"
                        , array(
                                'user_sport' => new Zend_Db_Expr("
                                    CASE WHEN SU.sport_id IS NOT NULL 
                                        THEN 1
                                        ELSE 0
                                    END
                                ")
                                ,'user_dafault'=>new Zend_Db_Expr("
                                    CASE WHEN SU.default IS NOT NULL 
                                        THEN 1
                                        ELSE 0
                                    END 
                                ")
                          )
                )
                ->order('S.order')
                 ;
         }else {
            $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('S' => 'sport')
                        , array('sport_id'=>'id_spo', 'sport_name'=>'spo_name'))
                ->join(array('SU' => 'sportsxuser'), "SU.sport_id = S.id_spo AND SU.user_id = {$uid}"
                        , array(
                                'user_sport' => new Zend_Db_Expr("
                                    CASE WHEN SU.sport_id IS NOT NULL 
                                        THEN 1
                                        ELSE 0
                                    END
                                ")
                                ,'user_dafault'=>new Zend_Db_Expr("
                                    CASE WHEN SU.default IS NOT NULL 
                                        THEN 1
                                        ELSE 0
                                    END 
                                ")
                          )
                )
                ->order('S.order')
                 ;
         }
        if ($result = $this->fetchAll($select)){
            return $result;
        }else {
            return new Object();
        }   
    }
    
    public function deleteSportsByUser($uid){
        $this->delete("user_id = {$uid}");
    }
    
    
}

