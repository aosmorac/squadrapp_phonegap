<?php

class App_Util_JsonTree extends App_Util_Tree { 
    
        protected $fields=array();
        protected $ajaxUrl;
        protected $images;
        protected $idContenedor;
        protected $selectedNode;
        protected $defaultOpenNode;
        
        
	function __construct($table = "tree", $add_fields = array("title" => "title", "type" => "type")) {
		parent::__construct($table);
                $fields=$add_fields;
               
                foreach ($this->fields as $key => $value) {
                    if(!array_key_exists($key, $add_fields)){
                        $fields[$key] = $value;
                    }
                   
                }
		$this->fields = $fields;
             
		$this->add_fields = $add_fields;
	}

	function create_node($data) {
                $id = parent::_create((int)$data[$this->fields["id"]], (int)$data[$this->fields["position"]],$data);
                
		if($id) {
			
			return  "{ \"status\" : 1, \"id\" : ".(int)$id." }";
		}
		return "{ \"status\" : 0 }";
	}
	function set_data($data) {
		if(count($this->add_fields) == 0) { return "{ \"status\" : 1 }"; }
		$s = "UPDATE `".$this->table."` SET `".$this->fields["id"]."` = `".$this->fields["id"]."` "; 
		foreach($this->add_fields as $k => $v) {
			if(isset($data[$k]))	$s .= ", `".$this->fields[$v]."` = \"".$this->db->escape($data[$k])."\" ";
			else					$s .= ", `".$this->fields[$v]."` = `".$this->fields[$v]."` ";
		}
		$s .= "WHERE `".$this->fields["id"]."` = ".(int)$data["id"];
		$this->db->query($s);
		return "{ \"status\" : 1 }";
	}
        function move_node($data) { 
		$id = parent::_move((int)$data["id"], (int)$data["ref"], (int)$data["position"], (int)$data["copy"]);
		if(!$id) return "{ \"status\" : 0 }";
		if((int)$data["copy"] && count($this->add_fields)) {
			$ids	= array_keys($this->_get_children($id, true));
			$data	= $this->_get_children((int)$data["id"], true);

			$i = 0;
			foreach($data as $dk => $dv) {
				$s = "UPDATE `".$this->table."` SET `".$this->fields["id"]."` = `".$this->fields["id"]."` "; 
				foreach($this->add_fields as $k => $v) {
					if(isset($dv[$k]))	$s .= ", `".$this->fields[$v]."` = \"".$this->db->escape($dv[$k])."\" ";
					else				$s .= ", `".$this->fields[$v]."` = `".$this->fields[$v]."` ";
				}
				$s .= "WHERE `".$this->fields["id"]."` = ".$ids[$i];
				$this->db->query($s);
				$i++;
			}
		}
		return "{ \"status\" : 1, \"id\" : ".$id." }";
	}
	function rename_node($data) { 
            return $this->set_data($data); }

	
	function remove_node($data) {
		$id = parent::_remove((int)$data["id"]);
		return "{ \"status\" : 1 }";
	}
	function get_children($data) {
               
                if($data["id"]!==NULL)
                    $data["id"]=(int)$data["id"];
		$tmp = $this->_get_children($data["id"]);
                
                if(!is_array($tmp)){
                    return "";
                }
         
		$result = array();
		
               
		foreach($tmp as $k => $v) {
                        
			$result[] = array(
				"attr" => array("id" => "node_".$v[$this->fields["id"]], "rel" => $v[$this->fields["type"]]),
				"data" => $v[$this->fields["title"]],
				"state" => ((int)$v[$this->fields["totalchildren"]]> 0) ? "closed" : ""
			);
		}
		return json_encode($result);
	}
	function search($data) {
		$this->db->query("SELECT `".$this->fields["left"]."`, `".$this->fields["right"]."` FROM `".$this->table."` WHERE `".$this->fields["title"]."` LIKE '%".$this->db->escape($data["search_str"])."%'");
		if($this->db->nf() === 0) return "[]";
		$q = "SELECT DISTINCT `".$this->fields["id"]."` FROM `".$this->table."` WHERE 0 ";
		while($this->db->nextr()) {
			$q .= " OR (`".$this->fields["left"]."` < ".(int)$this->db->f(0)." AND `".$this->fields["right"]."` > ".(int)$this->db->f(1).") ";
		}
		$result = array();
		$this->db->query($q);
		while($this->db->nextr()) { $result[] = "#node_".$this->db->f(0); }
		return json_encode($result);
	}
       
}
?>
