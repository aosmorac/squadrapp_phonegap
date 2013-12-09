<?php

class App_Util_Date {
	
	function __construct() {
	
	}
	
   /**
    * Devuelve la fecha asegurandose que el dia de la semana sea un dia laboral.
    * Si la fecha proporcionada no tiene dia laboral, se modifica al siguiente dia laboral.
    * @param Zend_Date $date
    * @return Zend_Date
    */
    static public function forceWorkingDay(Zend_Date $date){
//        Zend_Debug::dump($date->get(Zend_Date::WEEKDAY_8601));
        $dayOfWeek = $date->get(Zend_Date::WEEKDAY_8601);
        while ($dayOfWeek == 6 || $dayOfWeek == 7) {
        	$date->addDay(1);
            $dayOfWeek = $date->get(Zend_Date::WEEKDAY_8601);
//            Zend_Debug::dump($dayOfWeek, "dayOfWeek");
        }
        
        return $date;
    }
    
    /**
    * Devuelve un true si la fecha check esta dentro del rango de la fecha start y end.
    * si no esta dentro del rango devuelve un false.
    * @param $dt_start,$dt_check,$dt_end
    * @return boolean
    */
    static public function isDateBetween($dt_start, $dt_check, $dt_end){
        if(strtotime($dt_check) >= strtotime($dt_start) && strtotime($dt_check) <= strtotime($dt_end)) {
            return true;
        }
        return false;
    } 
    
    
    
    static public function getRangeHours($h1, $h2){
        $nSegs = $h2-$h1;
        $nhours = $nSegs/3600;
        $hours = array();
        for ($i=0; $i<$nhours; $i++){
            $hours[] = date("H",$h1+($i*3600)).":00:00";
        }
        return $hours;
    }
    
    
    static public function getSpanishWeekday($weekday){
        $days = array(
                 0=>'Domingo'
                ,1=>'Lunes'
                ,2=>'Martes'
                ,3=>'Miércoles'
                ,4=>'Jueves'
                ,5=>'Viernes'
                ,6=>'Sabado'
        );
        return $days[$weekday];
    }
    
	
}//fin de la clase