<?php
class menuModel extends ModelBase
{
	
   	public function menu()
	{   
    	$config = Config::singleton();
        
        $consulta = $this->db->prepare("SHOW TABLES FROM ".$config->get('dbname'));
        $consulta->execute();
        
        $menu = array();
        $db_prefix = $config->get('db_prefix');
        while ($row = $consulta->fetch()) {
            if ($db_prefix == '' or strstr($row[0],$db_prefix) )
			if (file_exists("setup/".$row[0].".php")){
			    include "setup/".$row[0].".php";
    	    	$menu[] = array($row[0],$table_label);
    	    }
    	}
       return $menu;
    }
}
?>
