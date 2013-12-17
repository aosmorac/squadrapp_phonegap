<?php

class Messages_Model_DbTable_Cometchat extends Zend_Db_Table_Abstract {

    protected $_name = "cometchat";
    protected $_primary = 'id';

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    /**
     * 
     * @param type $uid
     * @param type $start
     * @param type $ini
     * @return type
     */
    public function getLastTalkersByUser($uid, $start=0, $ini = ''){
        $where = "1=1 ";
        if ($ini != ''){
            $where .= "AND T.use_name LIKE '%{$ini}%' ";
        }
        $select = $this->select()
                  ->from(array("T"=>new Zend_Db_Expr("
                        (SELECT 
                                MAX(A.mid) AS mid, 
                                A.uid AS uid, 
                                MAX(A.date) AS date , 
                                A.message, 
                                A.unread AS unread, 
                                U.*
                        FROM ( 
                                SELECT DISTINCT 
                                        MAX(C.id) AS mid, 
                                        MAX(C.date) AS date,
                                        C.from AS uid, 
                                        C.message, 
                                        (count(C.read)-SUM(case when C.read = 1 then 1 else 0 end)) AS unread
                                FROM cometchat AS C 
                                WHERE C.to = {$uid} 
                                GROUP BY uid  

                                UNION 

                                SELECT DISTINCT 
                                        MAX(C.id) AS mid, 
                                        MAX(C.date) AS date,
                                        C.to AS uid,  
                                        C.message, 
                                        0 AS unread 
                                FROM cometchat AS C 
                                WHERE C.from = {$uid} 
                                GROUP BY uid 
                        ) AS A 
                        INNER JOIN user AS U 
                                ON U.id_user = A.uid AND A.uid != {$uid} 
                        GROUP BY uid 
                        ORDER BY mid DESC ) 
                    "))
                  ,array(
                      "mid","date","unread"
                      ,"message"=>new Zend_Db_Expr("(SELECT message FROM cometchat WHERE id = T.mid LIMIT 1)")
                      ,"id_user","Facebook_id","use_name","use_first_name"
                      ,"use_last_name","Facebook_link","Facebook_username"
                      ,"use_hometown_id","use_hometown_name","use_location_id"
                      ,"use_location_name","use_location_coordinates"
                      ,"use_gener","use_email","use_locale","use_visit"
                      ,"use_date"=>new Zend_Db_Expr("use_date + INTERVAL ".App_User::getTimeZone()." HOUR")
                      ,"lastactivity"
                      ,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-T.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
                    )
                  )
                ->setIntegrityCheck(false)
                ->joinleft(array('S' => 'cometchat_status'), "S.userid = T.id_user"
                        , array())
                ->where($where)
                ->order("date DESC")
                ->limit(10,$start)
                ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $users = $row->toArray();
         return $users;
    }

    
    
    
	/**
     * 
     * @param type $uid
     * @param type $start
     * @param type $ini
     * @return type
     */
    public function getLastTalkersByUserApp($uid, $start=0, $timezone=-5, $ini = ''){
        $where = "1=1 ";
        if ($ini != ''){
            $where .= "AND T.use_name LIKE '%{$ini}%' ";
        }
        $select = $this->select()
                  ->from(array("T"=>new Zend_Db_Expr("
                        (SELECT 
                                MAX(A.mid) AS mid, 
                                A.uid AS uid, 
                                MAX(A.date) AS date , 
                                A.message, 
                                A.unread AS unread, 
                                U.*
                        FROM ( 
                                SELECT DISTINCT 
                                        MAX(C.id) AS mid, 
                                        MAX(C.date) AS date,
                                        C.from AS uid, 
                                        C.message, 
                                        (count(C.read)-SUM(case when C.read = 1 then 1 else 0 end)) AS unread
                                FROM cometchat AS C 
                                WHERE C.to = {$uid} 
                                GROUP BY uid  

                                UNION 

                                SELECT DISTINCT 
                                        MAX(C.id) AS mid, 
                                        MAX(C.date) AS date,
                                        C.to AS uid,  
                                        C.message, 
                                        0 AS unread 
                                FROM cometchat AS C 
                                WHERE C.from = {$uid} 
                                GROUP BY uid 
                        ) AS A 
                        INNER JOIN user AS U 
                                ON U.id_user = A.uid AND A.uid != {$uid} 
                        GROUP BY uid 
                        ORDER BY mid DESC ) 
                    "))
                  ,array(
                      "mid","unread"
                      ,"date"=>new Zend_Db_Expr("date + INTERVAL ".$timezone." HOUR")
                      ,"message"=>new Zend_Db_Expr("(SELECT message FROM cometchat WHERE id = T.mid LIMIT 1)")
                      ,"id_user","Facebook_id","use_name","use_first_name"
                      ,"use_last_name","Facebook_link","Facebook_username"
                      ,"use_hometown_id","use_hometown_name","use_location_id"
                      ,"use_location_name","use_location_coordinates"
                      ,"use_gener","use_email","use_locale","use_visit"
                      ,"use_date"=>new Zend_Db_Expr("use_date + INTERVAL ".$timezone." HOUR")
                      ,"lastactivity"
                      //,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-T.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
                      ,'online' => new Zend_Db_Expr("IF(((UNIX_TIMESTAMP()-T.lastactivity) < 400 ), 1, 0)")
                    )
                  )
                ->setIntegrityCheck(false)
                ->joinleft(array('S' => 'cometchat_status'), "S.userid = T.id_user"
                        , array())
                ->where($where)
                ->order("date DESC")
                ->limit(5,$start)
                ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $users = $row->toArray();
         return $users;
    }
    
    
    
    /**
     * 
     * @param type $uid
     * @param type $start
     * @param type $ini
     * @return type
     */
    public function getNewTalkersByUserApp($uid, $nid=0, $timezone=-5, $ini = ''){
        $where = "1=1 AND T.mid > {$nid} ";
        if ($ini != ''){
            $where .= "AND T.use_name LIKE '%{$ini}%' ";
        }
        $select = $this->select()
                  ->from(array("T"=>new Zend_Db_Expr("
                        (SELECT 
                                MAX(A.mid) AS mid, 
                                A.uid AS uid, 
                                MAX(A.date) AS date , 
                                A.message, 
                                A.unread AS unread, 
                                U.*
                        FROM ( 
                                SELECT DISTINCT 
                                        MAX(C.id) AS mid, 
                                        MAX(C.date) AS date,
                                        C.from AS uid, 
                                        C.message, 
                                        (count(C.read)-SUM(case when C.read = 1 then 1 else 0 end)) AS unread
                                FROM cometchat AS C 
                                WHERE C.to = {$uid} 
                                GROUP BY uid  

                                UNION 

                                SELECT DISTINCT 
                                        MAX(C.id) AS mid, 
                                        MAX(C.date) AS date,
                                        C.to AS uid,  
                                        C.message, 
                                        0 AS unread 
                                FROM cometchat AS C 
                                WHERE C.from = {$uid} 
                                GROUP BY uid 
                        ) AS A 
                        INNER JOIN user AS U 
                                ON U.id_user = A.uid AND A.uid != {$uid} 
                        GROUP BY uid 
                        ORDER BY mid DESC ) 
                    "))
                  ,array(
                      "mid","unread"
                      ,"date"=>new Zend_Db_Expr("date + INTERVAL ".$timezone." HOUR")
                      ,"message"=>new Zend_Db_Expr("(SELECT message FROM cometchat WHERE id = T.mid LIMIT 1)")
                      ,"id_user","Facebook_id","use_name","use_first_name"
                      ,"use_last_name","Facebook_link","Facebook_username"
                      ,"use_hometown_id","use_hometown_name","use_location_id"
                      ,"use_location_name","use_location_coordinates"
                      ,"use_gener","use_email","use_locale","use_visit"
                      ,"use_date"=>new Zend_Db_Expr("use_date + INTERVAL ".$timezone." HOUR")
                      ,"lastactivity"
                      //,'online' => new Zend_Db_Expr("IF((UNIX_TIMESTAMP()-T.lastactivity) < 240 AND (S.status = 'busy' OR S.status = 'away' OR S.status = 'available'), 1, 0)")
                      ,'online' => new Zend_Db_Expr("IF(((UNIX_TIMESTAMP()-T.lastactivity) < 400 ), 1, 0)")
                    )
                  )
                ->setIntegrityCheck(false)
                ->joinleft(array('S' => 'cometchat_status'), "S.userid = T.id_user"
                        , array())
                ->where($where)
                ->order("date DESC")
                ->limit(10,0)
                ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $users = $row->toArray();
         return $users;
    }
	
	
	
    
    
    public function getMessagesChat($id_talker1=0, $id_talker2=0, $start=0, $lid=0){
        $where = "CHAT.id > {$lid} AND (
                    (CHAT.from = {$id_talker1} AND CHAT.to = {$id_talker2}) 
                     OR 
                    (CHAT.from = {$id_talker2} AND CHAT.to = {$id_talker1})
                  )";
        $select = $this->select()
                ->from(
                        array("CHAT"=>$this->_name) 
                        ,array('mid'=>'id'
                            ,'message'=>'message'
                            ,'date'=>new Zend_Db_Expr("(CHAT.date + INTERVAL ".App_User::getTimeZone()." HOUR)"))
                    )
                ->setIntegrityCheck(false)
                ->join(
                        array('USER' => 'user'), "USER.id_user = CHAT.from"
                        , array('user_id'=>'id_user','user_name'=>'use_name'
                            ,'user_first_name'=>'use_first_name'
                            ,'user_last_name'=>'use_last_name'
                            ,'Facebook_id'=>'Facebook_id'
                            ,'Facebook_link'=>'Facebook_link')
                    )
                ->where($where)
                ->order("CHAT.id DESC")
                ->limit(10,$start)
            ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $messages = $row->toArray();
         return $messages;
    }

    public function getMessagesChatApp($id_talker1=0, $id_talker2=0, $timezone=-5, $start=0, $lid=0){
        $where = "CHAT.id > {$lid} AND (
                    (CHAT.from = {$id_talker1} AND CHAT.to = {$id_talker2}) 
                     OR 
                    (CHAT.from = {$id_talker2} AND CHAT.to = {$id_talker1})
                  )";
        $select = $this->select()
                ->from(
                        array("CHAT"=>$this->_name) 
                        ,array('mid'=>'id'
                            ,'message'=>'message'
                            ,'date'=>new Zend_Db_Expr("(CHAT.date + INTERVAL ".$timezone." HOUR)"))
                    )
                ->setIntegrityCheck(false)
                ->join(
                        array('USER' => 'user'), "USER.id_user = CHAT.from"
                        , array('user_id'=>'id_user','user_name'=>'use_name'
                            ,'user_first_name'=>'use_first_name'
                            ,'user_last_name'=>'use_last_name'
                            ,'Facebook_id'=>'Facebook_id'
                            ,'Facebook_link'=>'Facebook_link')
                    )
                ->where($where)
                ->order("CHAT.id DESC")
                ->limit(10,$start)
            ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $messages = $row->toArray();
         return $messages;
    }
    
    public function getLastMessagesChat($id_talker1=0, $id_talker2=0, $lid=0){
        $where = "CHAT.id > {$lid} AND (
                    (CHAT.from = {$id_talker1} AND CHAT.to = {$id_talker2}) 
                     OR 
                    (CHAT.from = {$id_talker2} AND CHAT.to = {$id_talker1})
                  )";
        $select = $this->select()
                ->from(
                        array("CHAT"=>$this->_name) 
                        ,array('mid'=>'id'
                            ,'message'=>'message'
                            ,'date'=>new Zend_Db_Expr("(CHAT.date + INTERVAL ".App_User::getTimeZone()." HOUR)"))
                    )
                ->setIntegrityCheck(false)
                ->join(
                        array('USER' => 'user'), "USER.id_user = CHAT.from"
                        , array('user_id'=>'id_user','user_name'=>'use_name'
                            ,'user_first_name'=>'use_first_name'
                            ,'user_last_name'=>'use_last_name'
                            ,'Facebook_id'=>'Facebook_id'
                            ,'Facebook_link'=>'Facebook_link')
                    )
                ->where($where)
                ->order("CHAT.id DESC")
            ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchAll($select);
         $messages = $row->toArray();
         return $messages;
    }
    
    public function updateReadMessages($id_from, $id_to){
        $data = array('read'=>1);
        $where = new Zend_Db_Expr("`from` = {$id_to} AND `to` = {$id_from} AND `read` != 1");
        $this->update($data, $where);
    }
    
    
    public function totalUnreadMessages($uid){
        $select = $this->select()
                  ->from(array($this->_name)
                  ,array(
                      "unread"=>new Zend_Db_Expr("(count(`read`)-SUM(case when `read` = 1 then 1 else 0 end))")
                    )
                  )
                ->where(new Zend_Db_Expr("`to` = {$uid}"))
                ;
         //Zend_Debug::dump($select.''); die;
         $row = $this->fetchRow($select);
         $users = $row->toArray();
         return $users;
    }


}

