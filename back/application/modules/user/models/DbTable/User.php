<?php

class User_Model_DbTable_User extends Zend_Db_Table_Abstract {

    protected $_name = "user";
    protected $_primary = 'id_user';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    public function getUserFacebook($facebookId){
        $row = $this->fetchAll(
            $this->select()
                ->where("Facebook_id='{$facebookId}'")
                ->limit(1)
        );
        $row = $row->toArray();
		if ( count($row) >= 1 )
        	return $row[0];
    }
    
    public function getUserById($id){
        $row = $this->fetchRow(
            $this->select()
                ->where("id_user={$id}")
                ->limit(1)
        );
        $row = $row->toArray();
        return $row;
    }
    
    public function saveUserFacebook($infoUser){
    	
        //echo json_encode($infoUser); die;
        $idUser = $this->insert($infoUser);
        $row = $this->fetchAll(
            $this->select()
                ->where("id_user={$idUser}")
                ->limit(1)
        );
        $row = $row->toArray();
        return $row[0];
    }
    
    
    /*
     * Parametro $ids
     * String con ids separados por comas
     */
    public function getUsersById($ids, $order = false){
        if ($order) {
            $users = array();
            foreach ($ids as $id)
                $users[] = $this->getUserById($id);
        }else{
            $ids_string = implode(",",$ids);
            $select = $this->select()
                    ->where("id_user IN ({$ids_string})");
            $row = $this->fetchAll($select);
            $users = $row->toArray();
        }
        return $users;
    }
    
    
    
    /*
     * Parametro $ids
     * String con ids separados por comas
     */
    public function getUsersByIdsFids($ids, $fids, $order = false){
        if ($order) {
            $users = array();
            foreach ($ids as $id)
                $users[] = $this->getUserById($id);
        }else{
            $ids_string = implode(",",$ids);
            $select = $this->select()
                    ->where("id_user IN ({$ids_string}) AND Facebook_id IN({$fids})");
            $row = $this->fetchAll($select);
            $users = $row->toArray();
        }
        return $users;
    }
    
    
    
    public function getUsersByFacebookids($fids){
            $select = $this->select()
                    ->where("Facebook_id IN({$fids})")
                    ->order('use_name')
                    ;
            $row = $this->fetchAll($select);
            $users = $row->toArray();
        return $users;
    }
    
    
    /**
     * 
     * Usuarios por deporte y opciones de ubicación.
     * 
     * @param type $location
     * @param type $uid
     * @param type $start
     * @param type $ini
     * @param type $id_sport
     * @return type
     */
    public function getUsersLS($location=0, $uid=0, $start=0, $ini = '', $id_sport = 1, $limit=18){
        //Zend_Debug::dump($location);
        $city = explode( ',', $location['use_location_name'] );
        if (trim($city[0]) == ''){ return array(); }
        $where = "U.id_user != {$uid} AND U.use_location_name like '{$city[0]}%'";
        if ($ini != '') { $where .= "AND U.use_name LIKE '%{$ini}%'"; }
        $select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array(
                                'id_user','Facebook_id','use_name','use_first_name'
                                ,'use_last_name','Facebook_link','Facebook_username'
                                ,'use_hometown_id','use_hometown_name','use_location_id'
                                ,'use_location_name','use_location_coordinates'
                                ,'use_gener','use_email','use_locale','use_visit'
                                ,'use_date','lastactivity'
                                ,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-U.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->joinleft(array('S' => 'cometchat_status'), "S.userid = U.id_user"
                        , array())
                  ->where($where)
                  ->order(new Zend_Db_Expr("(UNIX_TIMESTAMP()-U.lastactivity) ASC, U.use_date DESC"))
                  ->limit($limit,$start);
                ;
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchAll($select);
        $users = $row->toArray();
        return $users;
    }
    
    /**
     * Todos los usuarios con limit
     * 
     * @param type $uid
     * @param type $limit
     * @return type
     */
    public function getUsersAll($uid=0, $limit=6){
        //Zend_Debug::dump($location);
        $where = "U.id_user != {$uid} ";
        $select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array(
                                'id_user','Facebook_id','use_name','use_first_name'
                                ,'use_last_name','Facebook_link','Facebook_username'
                                ,'use_hometown_id','use_hometown_name','use_location_id'
                                ,'use_location_name','use_location_coordinates'
                                ,'use_gener','use_email','use_locale','use_visit'
                                ,'use_date','lastactivity'
                                ,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-U.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->joinleft(array('S' => 'cometchat_status'), "S.userid = U.id_user"
                        , array())
                  ->where($where)
                  ->order(new Zend_Db_Expr("RAND()"))
                  ->limit($limit);
                ;
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchAll($select);
        $users = $row->toArray();
        return $users;
    }
    
    public function getTotalUsersLS($location, $uid, $start=0, $ini = '', $id_sport = 1){
        //Zend_Debug::dump($location);
        $city = explode( ',', $location['use_location_name'] );
        if (trim($city[0]) == ''){ return array(); }
        $where = "U.id_user != {$uid} AND U.use_location_name like '{$city[0]}%'";
        if ($ini != '') { $where .= "AND U.use_name LIKE '%{$ini}%'"; }
        $select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array('total' => new Zend_Db_Expr("count(id_user)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->where($where);
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchRow($select);
        $users = $row->toArray();
        return $users;
    }
    
    
    /**
     * 
     * Amigos por deporte y opciones de ubicación.
     * 
     * @param type $location
     * @param type $uid
     * @param type $friendsIds
     * @param type $start
     * @param type $ini
     * @param type $id_sport
     * @return type
     */
    public function getUsersFLS($location, $uid, $friendsIds, $start=0, $ini = '', $id_sport = 1){
        //Zend_Debug::dump($location);
        $city = explode( ',', $location['use_location_name'] );
        if (trim($city[0]) == ''){ return array(); }
        $where = "U.id_user != {$uid} AND U.use_location_name like '{$city[0]}%' AND U.id_user IN ({$friendsIds})";
        if ($ini != '') { $where .= "AND U.use_name LIKE '%{$ini}%'"; }
        $select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array(
                                'id_user','Facebook_id','use_name','use_first_name'
                                ,'use_last_name','Facebook_link','Facebook_username'
                                ,'use_hometown_id','use_hometown_name','use_location_id'
                                ,'use_location_name','use_location_coordinates'
                                ,'use_gener','use_email','use_locale','use_visit'
                                ,'use_date','lastactivity'
                                ,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-U.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->joinleft(array('S' => 'cometchat_status'), "S.userid = U.id_user"
                        , array())
                  ->where($where)
                  ->order(new Zend_Db_Expr("(UNIX_TIMESTAMP()-U.lastactivity) ASC, U.use_date DESC"))
                  ->limit(18,$start);
                ;
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchAll($select);
        $users = $row->toArray();
        return $users;
    }
    
    public function getTotalUsersFLS($location, $uid, $friendsIds, $start=0, $ini = '', $id_sport = 1){
        //Zend_Debug::dump($location);
        $city = explode( ',', $location['use_location_name'] );
        if (trim($city[0]) == ''){ return array(); }
        $where = "U.id_user != {$uid} AND U.use_location_name like '{$city[0]}%' AND U.id_user IN ({$friendsIds})";
        if ($ini != '') { $where .= "AND U.use_name LIKE '%{$ini}%'"; }
        $select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array('total' => new Zend_Db_Expr("count(id_user)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->where($where)
                ;
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchRow($select);
        $users = $row->toArray();
        return $users;
    }
    
    
    
    
    /**
     * 
     * @param type $user_id
     * @param type $limit
     * @param type $sta_id
     * @return Object
     */
    function getCurrentBooking($user_id, $limit, $sta_id = 0){
        $now = date("H:i:00");
        $today = date("Y-m-d");
        $where = "(SUBSTRING(FS.fie_sch_date,1,10) > '{$today}' OR (SUBSTRING(FS.fie_sch_date,1,10) = '{$today}' AND FS.fie_sch_hour > '{$now}')) AND BU.user_id = {$user_id}";
        if ($sta_id > 0) {
            $where .= " AND FS.sta_id = {$sta_id}";
        }
        $select = $this->select()
                ->setIntegrityCheck(false)
                ->from(array('BU' => 'booking_user')
                        , array('reg_date'=>'date', 'seconds'=>new Zend_Db_Expr("TIME_TO_SEC(TIMEDIFF(NOW(),BU.date))")))
                ->join(array('FS' => 'field_schedule'), "FS.id_fie_sch = BU.fie_sch_id "
                        , array('sta_id','fie_sch_currency'
                            ,'fie_sch_value','fie_sch_date'
                            , 'id_sch'=>new Zend_Db_Expr("FS.id_fie_sch")
                            , 'weekday'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_date,'%w')")
                            , 'day'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_date,'%d')")
                            , 'month'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_date,'%b')")
                            , 'fie_sch_hour'
                            , 'hour1'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_hour,'%H:%i')")
                            , 'hour2'=>new Zend_Db_Expr("ADDTIME(FS.fie_sch_hour,'01:00:00')")
                            ))
                ->join(array('TM' => 'vw_size_material'), "TM.id_fie = FS.fie_id "
                        , array('attribute_ids'
                                ,'attributes'
                                ,'id_fie'=>new Zend_Db_Expr("TM.id_fie")))
                ->join(array('BRANCH' => 'branch'), "BRANCH.id_bra = TM.bra_id",
                    array('id_bra','com_id',
                    'bra_name','bra_area','bra_neighborhood','bra_address',
                    'bra_phone','bra_email','bra_location','bra_coordinates','bra_alias'))
                ->join(array('COMPANY' => 'company'), "COMPANY.id_com = BRANCH.com_id "
                        , array('company'=>'com_name'))
                ->join(array('CITY' => 'city'), "CITY.ID = BRANCH.city_ID"
                        , array('city'=>'Name'))
                ->join(array('COUNTRY' => 'country'), "COUNTRY.Code = CITY.CountryCode"
                        , array('country'=>'Name'));
                ;
        $select->where(new Zend_Db_Expr($where));
         $select->group(new Zend_Db_Expr("FS.sta_id 
                                        ,FS.fie_sch_currency 
                                        ,FS.fie_sch_value 
                                        ,FS.fie_sch_date
                                        ,FS.fie_sch_hour
                                        ,TM.attribute_ids
                                        ,TM.attributes
                                        ,BRANCH.bra_name
                                        ,BRANCH.com_id
                                        ,BRANCH.bra_name
                                        ,BRANCH.bra_area
                                        ,BRANCH.bra_neighborhood
                                        ,BRANCH.bra_address
                                        ,BRANCH.bra_phone
                                        ,BRANCH.bra_email
                                        ,BRANCH.bra_location
                                        ,BRANCH.bra_coordinates
                                        ,BRANCH.bra_alias
                                        ,COMPANY.com_name
                                        ,CITY.Name
                                        ,COUNTRY.Name"));
        $select->order(new Zend_Db_Expr("FS.fie_sch_date, FS.fie_sch_hour"));
        $select->limit($limit);
        //Zend_Debug::dump($select.''); die;
        if ($result = $this->fetchAll($select)){
            return $result;
        }else {
            return new Object();
        }        
    }
    
    
    
    public function getPlayers($uid=0, $ini = '', $limit = 5){
        $where = "U.id_user != {$uid} ";
        if ($ini != '') { $where .= "AND U.use_name LIKE '%{$ini}%'"; }
        $select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array(
                                'id_user','Facebook_id','use_name','use_first_name'
                                ,'use_last_name','Facebook_link','Facebook_username'
                                ,'use_hometown_id','use_hometown_name','use_location_id'
                                ,'use_location_name','use_location_coordinates'
                                ,'use_gener','use_email','use_locale','use_visit'
                                ,'use_date','lastactivity'
                                //,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-U.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
								,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-U.lastactivity) < 62 ), 1, 0)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->joinleft(array('S' => 'cometchat_status'), "S.userid = U.id_user"
                        , array())
                  ->where($where)
                  ->order(new Zend_Db_Expr("(UNIX_TIMESTAMP()-U.lastactivity) ASC, U.use_date DESC"))
                  ->limit($limit);
                ;
        //Zend_Debug::dump($select.''); die;
        $row = $this->fetchAll($select);
        $users = $row->toArray();
        return $users;
    }


	public function updateLastActivity($uid=0){
		$data = Array('lastactivity'=>new Zend_Db_Expr("UNIX_TIMESTAMP()"));
		return $this->update($data, "id_user={$uid}");
	}
    
	
	public function getUserFriends($uid=0){
		$select = $this->select()
                  ->from(array("U"=>$this->_name)
                          ,array(
                                 'id'=>'id_user'
                                ,'Faacebook_id'=>'Facebook_id'
                                ,'name'=>'use_name'
                                ,'first_name'=>'use_first_name'
                                ,'last_name'=>'use_last_name'
                                ,'Facebook_link'
                                ,'Facebook_username'
                                ,'hometown_name'=>'use_hometown_name'
								,'location_name'=>'use_location_name'
								,'location_coordinates'=>'use_location_coordinates'
                                ,'gener'=>'use_gener'
                                ,'email'=>'use_email'
                                ,'locale'=>'use_locale'
                                ,'visit'=>'use_visit'
                                ,'date'=>'use_date'
                                ,'lastactivity'
                                ,'online' => new Zend_Db_Expr("IF(((UNIX_TIMESTAMP()-U.lastactivity) < 62 ), 1, 0)")
                              )
                          )
                  ->setIntegrityCheck(false)
                  ->join(array('UF' => 'user_friends')
                  		, "UF.friend2 = U.id_user AND UF.friend1 = {$uid}"
                        , array());
                ;
		//Zend_Debug::dump($select.''); die;
		$row = $this->fetchAll($select);
        $friends = $row->toArray();
        return $friends;
	}
    
    
    
}

