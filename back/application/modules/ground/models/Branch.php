<?php

class Ground_Model_Branch {

    private $registroDataTable;

    
    public function __construct() {
        $this->registroDataTable = new Ground_Model_DbTable_Branch();
    }
    
    
    /**
     * 
     * @param type $city_id
     * @param type $sport_id
     * @return type
     */
    public function getBranches($city_id, $sport_id, $ini, $lat='', $lng='', $uid=0, $likes=0, $limit=0){
        return $this->registroDataTable->getBranches($city_id, $sport_id, $ini, $lat, $lng, $uid, $likes, $limit)->toArray();
    }    
    
    
    public function searchBranches($term){
        return $this->registroDataTable->searchBranches($term)->toArray();
    }
    
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getBranch($id){
        $branch = $this->registroDataTable->getBranch($id)->toArray();
        return $branch[0];
    }
    
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getHeaderImages($id){
        $image = new Model_DbTable_Image();
        $attr = array('ima_table'=>'branch','ima_table_id'=>$id,'ima_alias'=>'header');
        $images = $image->getImages($attr);
        return $images->toArray();
    }
    
    
    /**
     * 
     * @param type $id
     */
    public function setVisitor($id){
        if (App_User::isLogged()){
            $bv = new Ground_Model_DbTable_BranchVisitor();
            $data = array('id_bra'=>$id, 'id_user'=>  App_User::getUserId());
            $bv->insert($data);
        }
    }
    
    
    /**
     * 
     * @param type $id
     * @param type $limit
     * @return type
     */
    public function getVisitors($id, $limit){
        $bv = new Ground_Model_DbTable_BranchVisitor();
        $user = new User_Model_User();
        $ids = $bv->getVisitorsIds($id, $limit);
        $users = $user->getUsersById($ids, true);
        return $users;
    }
    
    
    /**
     * 
     * @param type $id
     * @param type $limit
     * @return type
     */
    public function getVisitorsFriends($id, $limit){
        $bv = new Ground_Model_DbTable_BranchVisitor();
        $user = new User_Model_User();
        $ids = $bv->getVisitorsFriendsIds($id, App_User::getFriendsFacebookIds(), $limit);
        $users = $user->getUsersById($ids, true);
        return $users;
    }
    
    
    /**
     * 
     * @param type $id
     */
    public function getFields($id){
        $field_db = new Ground_Model_DbTable_Field();
        $fields = $field_db->getFieldsByBranch($id);
    }
    
    
    /**
     * 
     * @param type $bra_id
     * @param type $date
     * @param type $hour
     * @return type
     */
    public function getNextFieldFree($bra_id, $date, $hour){
        return $this->registroDataTable->getNextFieldFree($bra_id, $date, $hour)->toArray();
    }
    
    
    public function getSuggestFields($bra_id, $date, $hour, $limit, $type){
        return $this->registroDataTable->getSuggestFields($bra_id, $date, $hour, $limit, $type)->toArray();
    }

        
    /**
     * 
     * @param type $id
     * @return type
     */
    public function getFieldsFeatures($id){
        return $this->registroDataTable->getFieldsFeatures($id)->toArray();
    }
    
    
    /**
     * 
     * @param type $bra_id
     * @param type $date
     * @return type
     */
    public function getHoursByDay($bra_id, $date){
        $av_object = $this->registroDataTable->getHoursByDay($bra_id, $date);
        if (is_object($av_object)){
            $hours = $av_object->toArray();
        }else {
            $hours = array();
        }
        return $hours;
    }
    
    
    /**
     * 
     * @param type $ids_fie     //Ids de los campos
     * @param type $date        //Fecha
     * @param type $hour        //Hora
     * @return array            //Arreglo con canchas, fechas y horas disponibles
     */
    public function getBranchAvailabilityByHour($ids_fie, $date, $hour){
//        $this->registroDataTable->getHoursByDay(1, $date);Zend_Debug::dump($hour);
//        $hoursInput = explode(" - ", $hour);Zend_Debug::dump($hoursInput);
//        if (count($hoursInput)>1){
//            $h1 = strtotime($hoursInput[0]);
//            $h2 = strtotime($hoursInput[1]);
//        }else{
//            $h1 = $hour;
//            $h2 = date("H",$h1+3600).":00:00";Zend_Debug::dump($h1.' '.$h2);
//        }
//        $hours = App_Util_Date::getRangeHours($h1, $h2); 
        $av_object = $this->registroDataTable->getAvailabilityField($ids_fie, $date, $hour);
        if (is_object($av_object)){
            $availability = $av_object->toArray();
        }else {
            $availability = array();
        }
        return $availability;
    }
    
    
    
    public function getBookElement($id_fie_sch){
        $fshedule = new Ground_Model_DbTable_FieldSchedule();
        return $fshedule->getFieldBook($id_fie_sch);
    }
    
    
    /**
     * 
     * @param type $booking
     * @return boolean
     */
    public function saveBookingFromUser($booking){
        //Zend_Debug::dump($booking);
        $fshedule = new Ground_Model_DbTable_FieldSchedule();
        $fstatus = new Ground_Model_DbTable_FieldxStatus();
        $buser = new Ground_Model_DbTable_BookingUser();
        
        $fieldBook = $fshedule->getFieldBook($booking['fie_sch_id']);
        $sta_id = 2;
        $booking['user_id'] = App_User::getUserId();
        $booking['fie_id'] = $fieldBook['fie_id'];
        $booking['fie_sch_price'] = $fieldBook['fie_sch_value'];
        
        //Zend_Debug::dump($booking);
        
        $fieldStatus = array('fie_sch_id'=>$booking['fie_sch_id'],'sta_id'=>$sta_id, 'use_id'=>$booking['user_id']);
        
        if ($buser->insert($booking)) {
            if ($fstatus->insert($fieldStatus)){
                if ($fshedule->updateState($booking['fie_sch_id'], $sta_id)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    
    public function saveLike($bra_id, $use_id){
        $bl = new Ground_Model_DbTable_BranchLike();
        if ($use_id > 0){
            $data = array('bra_id'=>$bra_id, 'use_id'=>$use_id);
            if ($bl->insert($data)){
                return TRUE;
            }else {
                return FALSE;
            }
        }else {
            return FALSE;
        }
    }
    
    public function deleteLike($bra_id, $use_id){
        $bl = new Ground_Model_DbTable_BranchLike();
        if ($use_id > 0){
            $where = "bra_id={$bra_id} AND use_id={$use_id}";
            if ($bl->delete($where)){
                return TRUE;
            }else {
                return FALSE;
            }
        }else {
            return FALSE;
        }
    }
    
    

    

    
    
}