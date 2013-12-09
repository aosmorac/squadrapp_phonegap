<?


final class text extends field{

	function view(){
		return "Contenido HTML";
	}
	function bake_field (){
		return "<textarea class='mceEditor'  id=\"".$this->fieldname."\" name=\"".$this->fieldname."\"  >".$this->value."</textarea>";
	}
		
	function exec_add () {
			return addslashes(stripslashes($this->value));

	}
	function exec_edit () {
		if ($this->value != "")
				return addslashes(stripslashes($this->value));
		return '';
	}

}

							