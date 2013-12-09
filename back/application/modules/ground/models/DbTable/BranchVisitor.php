<?php

class Ground_Model_DbTable_BranchVisitor extends Zend_Db_Table_Abstract {

    protected $_name = "branch_visitor";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getVisitorsIds($bid, $limit){
        $select = $this->select()
                ->distinct()
                ->from($this->_name,array('id' => 'id_user'))
                ->where("id_bra = {$bid}")
                ->order("bra_vis_date DESC")
                ->limit($limit);
        $row = $this->fetchAll($select);
        $ids_2d = $row->toArray();
        $ids = array();
        foreach ( $ids_2d as $id ) {
            $ids[] = $id['id'];
        }
        return $ids;
    }
    
    public function getVisitorsFriendsIds($branch_id, $facebook_ids, $limit){
        if (trim($facebook_ids) == '')
            $facebook_ids = '-1';
        $select = $this->select()
                ->distinct()
                ->from(array("V"=>$this->_name),array('id' => 'id_user'))
                ->setIntegrityCheck(false)
                ->join(array('U' => 'user'), "U.id_user = V.id_user"
                        , array())
                ->where(new Zend_Db_Expr("V.id_bra = {$branch_id} AND U.Facebook_id IN ({$facebook_ids})"))
                ->order("V.bra_vis_date DESC")
                ->limit($limit);
        //Zend_Debug::dump($select.'');die;
        $row = $this->fetchAll($select);
        $ids_2d = $row->toArray();
        $ids = array();
        foreach ( $ids_2d as $id ) {
            $ids[] = $id['id'];
        }
        return $ids;
    }


}

