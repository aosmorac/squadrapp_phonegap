<?php

class User_Model_DbTable_UserAutochat extends Zend_Db_Table_Abstract {

    protected $_name = "auto_chat";
    
    protected $_primary = 'aut_cha_id';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    /**
     * 
     * @param int $user_id
     * @return array
     */
    public function getMessageByUser($user_id){
        $select = $this->select()
                  ->from(array("AC"=>$this->_name)
                          ,array(
                                'aut_cha_id','aut_cha_message'
                                ,'aut_cha_message_2','aut_cha_message_3'
                                ,'aut_cha_message_4','aut_cha_message_5'
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->joinleft(array('ACU' => 'auto_chatxuser')
                             , "ACU.aut_cha_id = AC.aut_cha_id AND ACU.use_id = {$user_id}"
                        , array('use_id'))
                  ->where(
                          new Zend_Db_Expr("DATE(NOW()) BETWEEN AC.aut_cha_from AND AC.aut_cha_until AND ACU.use_id IS NULL ")
                          )
                  ->order("AC.aut_cha_id ASC ")
                  ->limit(1);
                ;
        $message = $this->fetchRow($select);
        return $message->toArray();
    }
    
    
}

