<?php

class Ground_Model_DbTable_Branch extends Zend_Db_Table_Abstract {

    protected $_name = "branch";

    public function __construct() {
        $this->_setAdapter('APP');
    }
    
    /**
     * 
     * @param type $city_id
     * @param type $sport_id
     * @return type
     */
    public function getBranches($city_id=0, $sport_id=0, $ini='', $lat=0, $lng=0, $uid=0, $likes=0, $limit=0){ 
        if (trim($lat)=='' || trim($lng)==''){
            $distance = "0";
            $order = "RAND()";
        }else{
            $distance = "ROUND((acos(sin(radians(BRANCH.bra_lat)) * sin(radians({$lat})) + cos(radians(BRANCH.bra_lat)) * cos(radians({$lat})) * cos(radians(BRANCH.bra_lng) - radians({$lng}))) * 6378), 1)";
            $order = "(acos(sin(radians(BRANCH.bra_lat)) * sin(radians({$lat})) + cos(radians(BRANCH.bra_lat)) * cos(radians({$lat})) * cos(radians(BRANCH.bra_lng) - radians({$lng}))) * 6378) ASC";
        }
        $where = "ATTRIBUTE.father_id = 1 
                  AND BRANCH.bra_active = 1 ";
        if (trim($ini) != ''){
            $where .= "AND (
                    COMPANY.com_name LIKE '%{$ini}%' 
                    OR BRANCH.bra_name LIKE '%{$ini}%' 
                    OR BRANCH.bra_area LIKE '%{$ini}%' 
                    OR BRANCH.bra_address LIKE '%{$ini}%' 
                    ) ";
        }
        if ($city_id > 0){
            $where .= "AND BRANCH.city_ID = {$city_id} ";
        }
        if ($sport_id > 0){
            $where .= "AND FIELD.spo_id = {$sport_id} ";
        }
        if ($likes > 0) {
            $where .= "AND BL.bra_id IS NOT NULL ";
        }
        $select = $this->select()
                ->from(array('BRANCH' => 'branch')
                        , array(
                            'distance' => new Zend_Db_Expr($distance)
                            ,'n_fields'=>new Zend_Db_Expr('(SELECT COUNT(id_fie) FROM field WHERE bra_id = BRANCH.id_bra)')  
                            ,'low_price'=>new Zend_Db_Expr('(SELECT bra_pri_value FROM branch_prices WHERE bra_id = BRANCH.id_bra ORDER BY bra_pri_value ASC LIMIT 1)')  
                            ,'value'=>new Zend_Db_Expr("CONCAT(COMPANY.com_name, '-' ,BRANCH.bra_name)"),'id_bra', 'bra_name', 'bra_area', 'bra_neighborhood'
                    , 'bra_address', 'bra_phone', 'bra_email' , 'bra_location', 'bra_coordinates', 'fun_id'
                    , 'id2', 'idioma', 'bra_alias'))
                ->setIntegrityCheck(false)
                ->join(array('COMPANY' => 'company'), "COMPANY.id_com = BRANCH.com_id"
                        , array('com_name'))
                ->join(array('CITY' => 'city'), "CITY.ID = BRANCH.city_ID "
                        , array('city_name'=>'Name'))
                ->join(array('FIELD' => 'field'), "FIELD.bra_id = BRANCH.id_bra "
                        , array('nFields'=>new Zend_Db_Expr('COUNT(ATTRIBUTE.id_fie_att)')))
                ->join(array('FXA' => 'fieldxattribute'), "FXA.fie_id = FIELD.id_fie "
                        , array())
                ->join(array('ATTRIBUTE' => 'field_attribute'), "ATTRIBUTE.id_fie_att = FXA.fie_att_id "
                        , array('size'=>new Zend_Db_Expr("group_concat(DISTINCT ATTRIBUTE.fie_att_name separator ' - ')")))
                ->joinLeft(array('IMAGE' => 'image'), "IMAGE.ima_table_id = BRANCH.id_bra AND IMAGE.ima_table = 'branch' AND IMAGE.id_ima_kin = 3 "
                        , array('image'=>new Zend_Db_Expr("CASE WHEN IMAGE.url_image IS NULL THEN '' ELSE IMAGE.url_image END")))
                ->joinLeft(array('BL' => 'branch_like'), "BL.bra_id = BRANCH.id_bra AND BL.use_id = {$uid} "
                        , array('saved'=>new Zend_Db_Expr("IF(BL.bra_id IS NOT NULL, 1, 0)")))
                ;
        $select->where($where);
        $select->group(new Zend_Db_Expr('BRANCH.id_bra 
                                , COMPANY.com_name 
                                , BRANCH.bra_name 
                                , BRANCH.bra_area 
                                , BRANCH.bra_neighborhood
                                , BRANCH.bra_address
                                , BRANCH.bra_phone
                                , BRANCH.bra_email
                                , BRANCH.bra_location
                                , BRANCH.bra_coordinates
                                , BRANCH.fun_id
                                , CITY.Name 
                                , BRANCH.id2
                                , BRANCH.idioma
                                , BRANCH.bra_alias
                                , IMAGE.url_image'));
        $select->order(new Zend_Db_Expr($order));
        if ($limit>0) {
           $select->limit($limit); 
        }
        //Zend_Debug::dump($select.''); die;
        return $this->fetchAll($select);
    }    
    
    
    
    
    public function searchBranches($term){
        $term2 = utf8_encode($term);
        $where = "BRANCH.bra_active = 1 
                  AND (
                    COMPANY.com_name LIKE '%{$term}%' 
                    OR BRANCH.bra_name LIKE '%{$term}%' 
                    OR BRANCH.bra_area LIKE '%{$term}%' 
                    OR BRANCH.bra_address LIKE '%{$term}%' 
                    )";
        $select = $this->select()
                ->from(array('BRANCH' => 'branch'), array('value'=>'bra_address','id_bra', 'bra_name', 'bra_area', 'bra_neighborhood'
                    , 'bra_address', 'bra_phone', 'bra_email' , 'bra_location', 'bra_coordinates', 'fun_id'
                    , 'id2', 'idioma', 'bra_alias'))
                ->setIntegrityCheck(false)
                ->join(array('COMPANY' => 'company'), "COMPANY.id_com = BRANCH.com_id"
                        , array('com_name'))
                ->join(array('CITY' => 'city'), "CITY.ID = BRANCH.city_ID "
                        , array('city_name'=>'Name'))
                ->join(array('FIELD' => 'field'), "FIELD.bra_id = BRANCH.id_bra "
                        , array('nFields'=>new Zend_Db_Expr('COUNT(ATTRIBUTE.id_fie_att)')))
                ->join(array('FXA' => 'fieldxattribute'), "FXA.fie_id = FIELD.id_fie "
                        , array())
                ->join(array('ATTRIBUTE' => 'field_attribute'), "ATTRIBUTE.id_fie_att = FXA.fie_att_id "
                        , array('size'=>new Zend_Db_Expr("group_concat(DISTINCT ATTRIBUTE.fie_att_name separator ' - ')")))
                ->joinLeft(array('IMAGE' => 'image'), "IMAGE.ima_table_id = BRANCH.id_bra AND IMAGE.ima_table = 'branch' AND IMAGE.id_ima_kin = 3 "
                        , array('image'=>new Zend_Db_Expr("CASE WHEN IMAGE.url_image IS NULL THEN '' ELSE IMAGE.url_image END"))
                );
        $select->where($where);
        $select->group(new Zend_Db_Expr('BRANCH.id_bra 
                                , COMPANY.com_name 
                                , BRANCH.bra_name 
                                , BRANCH.bra_area 
                                , BRANCH.bra_neighborhood
                                , BRANCH.bra_address
                                , BRANCH.bra_phone
                                , BRANCH.bra_email
                                , BRANCH.bra_location
                                , BRANCH.bra_coordinates
                                , BRANCH.fun_id
                                , CITY.Name 
                                , BRANCH.id2
                                , BRANCH.idioma
                                , BRANCH.bra_alias
                                , IMAGE.url_image'));
        $select->order(new Zend_Db_Expr('RAND()'));
        $select->limit(10);
        //Zend_Debug::dump($select.''); die;
        return $this->fetchAll($select);
    }    

    
    
    
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getBranch($id){
        $where = "BRANCH.id_bra = {$id}";
        $select = $this->select()
                ->from(array('BRANCH' => 'branch'), array('id_bra','com_id',
                    'bra_name','bra_area','bra_neighborhood','bra_address',
                    'bra_phone','bra_email','bra_location','bra_coordinates',
                    'fun_id','city_ID','id2','idioma','bra_alias'))
                ->setIntegrityCheck(false)
                ->join(array('COMPANY' => 'company'), "COMPANY.id_com = BRANCH.com_id "
                        , array('company'=>'com_name'))
                ->join(array('CITY' => 'city'), "CITY.ID = BRANCH.city_ID"
                        , array('city'=>'Name'))
                ->join(array('COUNTRY' => 'country'), "COUNTRY.Code = CITY.CountryCode"
                        , array('country'=>'Name'));
        $select->where($where);
        return $this->fetchAll($select);
    }
    
    
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getFieldsFeatures($id){
        $where = "bra_id = {$id}";
        $select = $this->select()
                ->from(array('TM' => 'vw_size_material')
                        , array('bra_id'
                                ,'spo_id'
                                ,'attribute_ids'
                                ,'attributes'
                                ,'value'=>'attributes'
                                ,'ids_fie'=>new Zend_Db_Expr("GROUP_CONCAT(id_fie SEPARATOR ', ')")))
                ->setIntegrityCheck(false);
        $select->where($where);
        $select->group(new Zend_Db_Expr("bra_id 
                                        ,spo_id 
                                        ,attribute_ids 
                                        ,attributes"));
        $select->order('attributes ASC');
        return $this->fetchAll($select);
    }
    
    
    
    
    /**
     * 
     * @param type $ids_fie
     * @param type $date
     * @param type $hour
     * @param type $range
     * @return type
     */
    public function getAvailabilityField($ids_fie = '', $date = '', $hour = '', $range='exact'){
        $wCompany = "";
        $wBranch = "";
        $wBranchSchedule = "";
        $wFieldSchedule = "";
        $wSizeMaterial = "";
        if (trim($ids_fie) != '') {
            $wFieldSchedule .= " AND FS.fie_id IN ({$ids_fie})";
        }
        if (trim($date) != '') {
            $wFieldSchedule .= " AND FS.fie_sch_date = '{$date}'";
        }
        if (trim($hour) != '') {
            $wFieldSchedule .= " AND FS.fie_sch_hour = '{$hour}'";
        }
        $select = $this->select()
                ->from(array('C' => 'company')
                         , array('com_name')
                         )
                ->setIntegrityCheck(false)
                ->join(array('B' => 'branch')
                        , "C.id_com = B.com_id"
                        , array('bra_name','bra_area','bra_neighborhood'
                            ,'bra_address','bra_phone','bra_email'
                            ,'bra_location','bra_coordinates','bra_alias')
                        )
                ->join(array('BS' => 'branch_schedule')
                        , "BS.bra_id = B.id_bra"
                        , array('bra_id')
                        )
                ->join(array('FS' => 'field_schedule')
                        , new Zend_Db_Expr("FS.bra_sch_id = BS.id_bra_sch".$wFieldSchedule)
                        , array('id_fie_sch','fie_id','sta_id'
                            ,'fie_sch_currency','fie_sch_value','fie_sch_date'
                            ,'fie_sch_day','fie_sch_hour','id_fie_sch')
                        )
                ->join(array('SM' => 'vw_size_material')
                        , "SM.id_fie = FS.fie_id"
                        , array('attribute_ids','attributes')
                        )
                ->join(array('CITY' => 'city')
                        , "CITY.ID = B.city_ID"
                        , array('city'=>'name')
                        )
                ->order('FS.sta_id ASC')
        ;
        
        if ($range == 'exact'){
            $select->limit(1);
        }
        
        //Zend_Debug::dump($select.''); die;
        
        return $this->fetchRow($select);
        
    }
    
    
    /**
     * 
     * @param type $bra_id
     * @param type $date
     * @param type $hour
     * @return type
     */
    public function getNextFieldFree($bra_id, $date, $hour){
        $where = "TM.bra_id = {$bra_id} AND FS.fie_sch_date >= '$date' AND FS.fie_sch_hour > '{$hour}' AND FS.sta_id = 1";
        $select = $this->select()
                ->from(array('FS' => 'field_schedule')
                        , array('sta_id','fie_sch_currency'
                            ,'fie_sch_value','fie_sch_date','fie_sch_hour', 'ids_sch'=>new Zend_Db_Expr("GROUP_CONCAT(FS.id_fie_sch SEPARATOR ',')")))
                ->setIntegrityCheck(false)
                ->join(array('TM' => 'vw_size_material'), "TM.id_fie = FS.fie_id "
                        , array('attribute_ids'
                                ,'attributes'
                                ,'ids_fie'=>new Zend_Db_Expr("GROUP_CONCAT(TM.id_fie SEPARATOR ', ')")));
        $select->where($where);
         $select->group(new Zend_Db_Expr("FS.sta_id 
                                        ,FS.fie_sch_currency 
                                        ,FS.fie_sch_value 
                                        ,FS.fie_sch_date
                                        ,FS.fie_sch_hour
                                        ,TM.attribute_ids
                                        ,TM.attributes"));
        $select->order(new Zend_Db_Expr("FS.fie_sch_date, FS.fie_sch_hour"));
        $select->limit(1);
        //Zend_Debug::dump($select.''); die;
        if ($result = $this->fetchRow($select)){
            return $result;
        }else {
            return $this->getNextField($bra_id, $date, $hour);
        }        
        
    }
    
    
    /**
     * 
     * @param type $bra_id
     * @param type $date
     * @param type $hour
     * @return type
     */
    public function getNextField($bra_id, $date, $hour){
        $where = "TM.bra_id = {$bra_id} AND FS.fie_sch_date >= '$date' AND FS.fie_sch_hour > '{$hour}' ";
        $select = $this->select()
                ->from(array('FS' => 'field_schedule')
                        , array('sta_id','fie_sch_currency'
                            ,'fie_sch_value','fie_sch_date','fie_sch_hour'))
                ->setIntegrityCheck(false)
                ->join(array('TM' => 'vw_size_material'), "TM.id_fie = FS.fie_id "
                        , array('attribute_ids'
                                ,'attributes'
                                ,'ids_fie'=>new Zend_Db_Expr("GROUP_CONCAT(TM.id_fie SEPARATOR ', ')")));
        $select->where($where);
         $select->group(new Zend_Db_Expr("FS.sta_id 
                                        ,FS.fie_sch_currency 
                                        ,FS.fie_sch_value 
                                        ,FS.fie_sch_date
                                        ,FS.fie_sch_hour
                                        ,TM.attribute_ids
                                        ,TM.attributes"));
        $select->order(new Zend_Db_Expr("FS.fie_sch_date, FS.fie_sch_hour"));
        $select->limit(1);
        //Zend_Debug::dump($select.''); die;
        return $this->fetchRow($select);        
    }
    
    
    function getSuggestFields($bra_id, $date, $hour, $limit, $type){
        $now = date("H:i:00");
        if ($type==1) {
            $where = "TM.bra_id = {$bra_id} AND FS.fie_sch_date = '$date' AND FS.sta_id = 1 AND FS.fie_sch_hour > '{$now}'";
        }else if ($type==2) {
            $where = "TM.bra_id = {$bra_id} AND FS.fie_sch_date >= '$date' AND FS.fie_sch_hour = '{$hour}' AND FS.sta_id = 1";
        }else if ($type==3) {
            $where = "TM.bra_id != {$bra_id} AND FS.fie_sch_date = '$date' AND FS.fie_sch_hour = '{$hour}' AND FS.sta_id = 1 AND FS.fie_sch_hour > '{$now}'";
        }else if ($type==4) {
            $where = "TM.bra_id != {$bra_id} AND FS.fie_sch_date = '$date' AND FS.fie_sch_hour != '{$hour}' AND FS.sta_id = 1 AND FS.fie_sch_hour > '{$now}'";
        }
        $select = $this->select()
                ->from(array('FS' => 'field_schedule')
                        , array('sta_id','fie_sch_currency'
                            ,'fie_sch_value','fie_sch_date'
                            , 'ids_sch'=>new Zend_Db_Expr("GROUP_CONCAT(FS.id_fie_sch SEPARATOR ', ')")
                            , 'weekday'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_date,'%w')")
                            , 'day'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_date,'%d')")
                            , 'month'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_date,'%b')")
                            , 'fie_sch_hour'
                            , 'hour1'=>new Zend_Db_Expr("DATE_FORMAT(FS.fie_sch_hour,'%H:%i')")
                            , 'hour2'=>new Zend_Db_Expr("ADDTIME(FS.fie_sch_hour,'01:00:00')")
                            ))
                ->setIntegrityCheck(false)
                ->join(array('TM' => 'vw_size_material'), "TM.id_fie = FS.fie_id "
                        , array('attribute_ids'
                                ,'attributes'
                                ,'ids_fie'=>new Zend_Db_Expr("GROUP_CONCAT(TM.id_fie SEPARATOR ', ')")))
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
    
    
    
    
    
    /**
     * 
     * @param type $bra_id
     * @param type $date
     * @return type
     */
    public function getHoursByDay($bra_id, $date){
        $today = date("Y-m-d");
        $now = date("H:i:00");
        $mkdate = strtotime($date);
        $weekday = date("N",$mkdate)-1;
        $where = "bra_id = {$bra_id} AND weekday = {$weekday}";
        if ($today == $date) 
            $where .= " AND hour>'{$now}'";
        $select = $this->select()
                ->from(array('branch_schedule')
                        , array('hour' => new Zend_Db_Expr('SUBSTRING(hour,1,5)')
                            ,'value' => new Zend_Db_Expr('SUBSTRING(hour,1,5)'))
                      )
                ->setIntegrityCheck(false);
        $select->where($where);
        $select->order('hour ASC');
        //Zend_Debug::dump($date); Zend_Debug::dump($select.''); die;
        return $this->fetchAll($select);
    }
    
    


}

