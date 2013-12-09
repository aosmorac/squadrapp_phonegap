<?php

class General_Model_Cron {

    
    public function __construct() {
        
    }
    
    
    
    public function initializeSchedule($item_id = 0, $item = 'branch'){
        $days = 70;
        $field_dbtable = new Ground_Model_DbTable_Field();
        if ($item_id>0){
        	if ($item == 'field'){
            	$fields = $field_dbtable->getAllFieldById($item_id)->toArray();
			}elseif ($item == 'branch'){
            	$fields = $field_dbtable->getAllFieldByBranch($item_id)->toArray();
			}
        }else{
            $fields = $field_dbtable->getAllFields()->toArray();
		}
        $branch_schedule_dbtabel = new Ground_Model_DbTable_BranchSchedule();
        $pricesByField = new Ground_Model_DbTable_FieldPrices();
        $field_schedule = new Ground_Model_DbTable_FieldSchedule();
        $fieldxstatus = new Ground_Model_DbTable_FieldxStatus() ;
        foreach ($fields AS $field) {
            $lastLoad = $field_schedule->getLastDate($field['id_fie']);
            for ($i=0; $i<=$days; $i++){
                $mkdate = mktime(0, 0, 0, date("m"), date("d")+$i, date("Y"));
                $date = date("Y-m-d",$mkdate);
                $weekday = date("N",$mkdate)-1;
                $schedule = $branch_schedule_dbtabel->getScheduleByBranch($field['bra_id'], $weekday)->toArray();
                foreach ($schedule AS $daily) {
                    $data = array();
                    $data['fie_id'] = $field['id_fie'];
                    $data['bra_sch_id'] = $daily['id_bra_sch'];
                    $data['sta_id'] = 1;
                    $price = $pricesByField->getPriceId($field['id_fie'], $daily['id_bra_sch']);
                    $data['fie_sch_currency'] = $price['currency'];
                    $data['fie_sch_value'] = $price['value'];
                    $data['fie_sch_date'] = $date;
                    $data['fie_sch_day'] = $daily['day'];
                    $data['fie_sch_hour'] = $daily['hour'];
                    if (!isset($lastLoad->fie_sch_date) || $lastLoad->fie_sch_date < $data['fie_sch_date'] || ($lastLoad->fie_sch_date == $data['fie_sch_date'] && $lastLoad->fie_sch_hour < $data['fie_sch_hour'])) {
                        if ($fie_sch_id = $field_schedule->insert($data)){
                            $first_status = array('fie_sch_id'=>$fie_sch_id, 'sta_id'=>1, 'use_id'=>0);
                            $fieldxstatus->insert($first_status);
                            Zend_Debug::dump($data, "Campo: {$field['id_fie']} ---- Fecha: {$data['fie_sch_date']} ---- Hora: {$data['fie_sch_hour']}");
                        }
                    }
                }
            }
        }
        //Zend_Debug::dump($fields);
    }
    

}