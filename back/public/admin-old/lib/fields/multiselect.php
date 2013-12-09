<?

final class multiselect extends field{

	function view(){
		echo $this->value;
	
	}
	function bake_field (){
		echo $this->bake_multicombo($this->ninjaState->ninjaConfig->db_prefix.$this->fieldname."s",$this->fieldname,$this->value);				
						
	}
		
	function exec_add () {
		return implod($this->value);

	}
	function exec_edit () {
		return 	implod($this->value);
	}
	
	
	function bake_multicombo($tablax2,$select_name,$ids_selected){
	$arg = mysql_query("SELECT id, ".$select_name." from $tablax2 order by ".$select_name." ASC",$this->ninjaConfig->link);	
	$output = "<select name=\"".$select_name."[]\" id=\"".$select_name."\" MULTIPLE='multiple' size='6' width='100' ".">";

	//$this->toError("SELECT id, ".$select_name." from $tablax2 order by ".$select_name." ASC");
	$ids_selected = explode(",",$ids_selected);
	/* ///$this->toError(implod($ids_selected)); */
	while ($row=mysql_fetch_array($arg)){
		
		$output .= "<option value=\"".$row['id']."\"";
		if (in_array($row['id'] ,$ids_selected)) $output .= " selected";
		$output .=">".$row[$select_name]."</option>";
	}
	
	$output .= "</select>";
	//$this->toError("BAKE_MULTICOMBO intenta abrir la tabla ".$tablax2);
	return $output;
	}


}

